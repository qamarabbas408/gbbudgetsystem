<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SapUpload; 
use App\Models\SapDump;
class SapUploadController extends Controller
{
    public function index()
    {
        return view('sapuploads.index');
    }

    public function storeBatchworks(Request $request)
    {
        try {
            $data = $request->input('data');
            $asOfDate = $request->input('as_of_date');
            $financialYear = $request->input('financial_year');

            // DB::beginTransaction();
            // 1. Create the Master Upload Record (The "Batch")
            // This gives us the ID the database is complaining about
            $upload = \App\Models\SapUpload::create([
                'financial_year' => $financialYear,
                'report_date' => $asOfDate,
                'file_name' => 'sap_dump_upload_'.now().'_.xlsx', // You can pass real name if you want
            ]);

            // Prepare the batch
            $insertData = [];
            foreach ($data as $row) {
                $insertData[] = [
                    'sap_upload_id' => $upload->id,
                    'adp_no' => $row['ADP_NO'],
                    'wbs_element' => $row['WBS'],
                    'project_description' => $row['Description'], // Check this matches migration
                    'final_budget' => $row['Final_Budget'],
                    'releases' => $row['Releases'],
                    'expenditure' => $row['Expenditure'],
                    'financial_year' => $financialYear,
                    'as_of_date' => $asOfDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            \App\Models\SapDump::insert($insertData);

            $mappings = \App\Models\DepartmentMapping::orderBy('id', 'asc')->get();
            foreach ($mappings as $rule) {
                \App\Models\SapDump::where('sap_upload_id', $upload->id)
                    ->whereBetween('adp_no', [$rule->start_adp, $rule->end_adp])
                    ->update(['department_id' => $rule->department_id]);
            }

            return response()->json([
                'status' => 'success',
                'message' => count($data).' records saved!',
            ]);

        } catch (\Exception $e) {
            // This will send the actual error message to your Toast
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeBatch(Request $request)
    {
        // 1. Validation

        $request->validate(['data' => 'required|array', 'as_of_date' => 'required|date', 'financial_year' => 'required']);

        $data = $request->input('data');
        $asOfDate = $request->input('as_of_date');
        $financialYear = $request->input('financial_year');

        // 2. Start Transaction
        DB::beginTransaction();

        try {
                // NEW STEP: Set all previous snapshots to inactive
            \App\Models\SapUpload::where('is_active', true)->update(['is_active' => false]);

            // 3. Create the Master Upload Record
            $upload = \App\Models\SapUpload::create([
                'financial_year' => $financialYear,
                'report_date' => $asOfDate,
                'file_name' => 'sap_dump_'.now()->format('Ymd_His').'.xlsx',
                'is_active' => true, // Mark this new upload as active

            ]);

            // 4. Prepare Data
            $insertData = [];
            $now = now();

            foreach ($data as $row) {
                $wbs = trim($row['WBS']);

                // Logic: Extract everything after the last hyphen '-'
                $headCode = str_contains($wbs, '-') ? last(explode('-', $wbs)) : null;
                $insertData[] = [
                    'sap_upload_id' => $upload->id, // The ID from the record we just created
                    'adp_no' => trim($row['ADP_NO']),
                    'wbs_element' => trim($row['WBS']),
                    'head_code' => $headCode,
                    'project_description' => trim($row['Description']),
                    'final_budget' => $row['Final_Budget'] ?? 0,
                    'releases' => $row['Releases'] ?? 0,
                    'expenditure' => $row['Expenditure'] ?? 0,
                    'financial_year' => $financialYear,
                    'as_of_date' => $asOfDate,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 5. Bulk Insert (Laravel handles the batching internally here)
            \App\Models\SapDump::insert($insertData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => count($data).' records saved successfully in Batch #'.$upload->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Processing Error: '.$e->getMessage(),
            ], 500);
        }
    }

    // 1. List all snapshots
    public function list()
    {
        // withCount('sapDumps') automatically calculates how many projects are in each dump
        $uploads = SapUpload::withCount('sapDumps')
            ->orderBy('report_date', 'desc')
            ->get();

        return view('sapuploads.list', compact('uploads'));
    }

    // 2. Set a specific snapshot as ACTIVE
    public function activate($id)
    {
        DB::beginTransaction();
        try {
            // Step A: Set ALL to inactive
            SapUpload::where('is_active', true)->update(['is_active' => false]);

            // Step B: Set the selected one to active
            $upload = SapUpload::findOrFail($id);
            $upload->is_active = true;
            $upload->save();

            DB::commit();

            return back()->with('success', "Snapshot #{$id} is now the default for all reports.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to activate snapshot.');
        }
    }

    // 3. Delete a snapshot and all its 4,000+ linked project rows
    public function destroy($id)
    {
        $upload = SapUpload::findOrFail($id);

        // Because of the 'cascade' on the migration,
        // deleting the parent automatically deletes all linked sap_dumps rows.
        $upload->delete();

        return back()->with('success', 'Snapshot and all associated data deleted successfully.');
    }
}
