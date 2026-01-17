<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentMapping;
use App\Models\SapDump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentMappingController extends Controller
{
    // 1. Show the Mapping Window
    public function index()
    {
        $departments = Department::with('sector')->get();
        $mappings = DepartmentMapping::with('department.sector')->get();

        return view('settings.mappings', compact('departments', 'mappings'));
    }

    // 2. Save a New Rule and Auto-Apply it
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'start_adp' => 'required',
            'end_adp' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Save the rule
            $mapping = DepartmentMapping::create($request->all());

            // APPLY THE RULE: Update all projects in this ADP range
            SapDump::whereBetween('adp_no', [$mapping->start_adp, $mapping->end_adp])
                ->update(['department_id' => $mapping->department_id]);

            DB::commit();

            return back()->with('success', 'Rule applied! Projects in range '.$mapping->start_adp.' to '.$mapping->end_adp.' have been assigned.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    // 3. Re-Sync All (Useful after a fresh SAP Upload)
    public function syncAll()
    {
        $rules = DepartmentMapping::all();

        DB::beginTransaction();
        try {
            foreach ($rules as $rule) {
                SapDump::whereBetween('adp_no', [$rule->start_adp, $rule->end_adp])
                    ->update(['department_id' => $rule->department_id]);
            }
            DB::commit();

            return back()->with('success', 'All projects re-synchronized based on active rules!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'start_adp' => 'required',
            'end_adp' => 'required',
        ]);

        $mapping = DepartmentMapping::findOrFail($id);

        // 1. First, nullify projects currently in the OLD range
        SapDump::whereBetween('adp_no', [$mapping->start_adp, $mapping->end_adp])
            ->update(['department_id' => null]);

        // 2. Update the rule
        $mapping->update($request->only(['department_id', 'start_adp', 'end_adp']));

        // 3. Apply the NEW range
        SapDump::whereBetween('adp_no', [$mapping->start_adp, $mapping->end_adp])
            ->update(['department_id' => $mapping->department_id]);

        return back()->with('success', 'Mapping rule updated and projects re-assigned.');
    }

    public function destroy($id)
    {
        $mapping = DepartmentMapping::findOrFail($id);

        // Un-map the projects before deleting the rule
        SapDump::whereBetween('adp_no', [$mapping->start_adp, $mapping->end_adp])
            ->update(['department_id' => null]);

        $mapping->delete();

        return back()->with('success', 'Rule deleted. Associated projects are now unmapped.');
    }
}
