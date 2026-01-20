<?php
namespace App\Http\Controllers;

use App\Models\AdpFormulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdpFormulationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'data' => 'required|array',
            'financial_year' => 'required|string'
        ]);

        $data = $request->input('data');
        $fy = $request->input('financial_year');

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                // 2. Logic: Extract and clean numeric values
                $estCost = (float)($row['Est_Cost'] ?? 0);
                $expUptoJune = (float)($row['Exp_June'] ?? 0);
                
                // 3. CALCULATED FIELD: Throw-forward
                // This ensures liability is mathematically correct regardless of Excel errors
                $throwForward = $estCost - $expUptoJune;

                // 4. Update or Create logic
                // We use adp_no as the unique key to prevent duplicates
                AdpFormulation::updateOrCreate(
                    ['adp_no' => trim($row['ADP_NO'])],
                    [
                        'scheme_name'         => trim($row['Description']),
                        'sector_code'         => $row['Sector'] ?? null,
                        'district_name'       => $row['District'] ?? null,
                        'halqa_code'          => $row['Halqa'] ?? null,
                        'head_code'           => $row['Head'] ?? null,
                        'is_approved'         => $row['Status'] === 'Approved' ? true : false,
                        'estimated_cost'      => $estCost,
                        'exp_upto_june'       => $expUptoJune,
                        'throw_forward'       => $throwForward,
                        'original_allocation' => (float)($row['Allocation'] ?? 0),
                        // Note: Final Budget/Releases stay at 0 until SAP Sync happens
                        'financial_year'      => $fy,
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'status' => 'success', 
                'message' => count($data) . ' ADP schemes have been synchronized.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 
                'message' => 'Database Sync Failed: ' . $e->getMessage()
            ], 500);
        }
    }
}