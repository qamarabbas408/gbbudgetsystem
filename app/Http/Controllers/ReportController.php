<?php

namespace App\Http\Controllers;

use App\Models\SapDump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function adpSummary(Request $request)
    {
        // 1. Get the Active Snapshot using our helper
        $activeSnapshot = \App\Models\SapUpload::getActiveSnapshot();

        if (! $activeSnapshot) {
            return redirect()->route('sap.upload')->with('error', 'Please upload a SAP dump first.');
        }

        // 2. Fetch projects belonging specifically to this snapshot
        $projects = SapDump::with(['department.sector'])
            ->notSdg()
            ->where('sap_upload_id', $activeSnapshot->id) // Filter by the specific batch ID
            ->select('adp_no', 'financial_year', 'project_description', 'department_id',
                DB::raw('SUM(final_budget) as total_allocation'),
                DB::raw('SUM(releases) as total_releases'),
                DB::raw('SUM(expenditure) as total_expenditure')
            )
            ->groupBy('adp_no', 'financial_year', 'project_description', 'department_id')
            ->get();

        return view('reports.adp_summary', [
            'projects' => $projects,
            'latestDate' => $activeSnapshot->report_date, // Pass the snapshot date to the UI
        ]);
    }

    public function sdgSummary(Request $request)
    {
        // $latestDate = SapDump::max('as_of_date');

        $activeSnapshot = \App\Models\SapUpload::getActiveSnapshot();

        if (! $activeSnapshot) {
            return redirect()->route('sap.upload')->with('error', 'Please upload a SAP dump first.');
        }

        $projects = SapDump::with(['department.sector'])
            ->isSdg() // <--- Filter for ONLY SG projects
            ->where('sap_upload_id', $activeSnapshot->id)
            ->select('adp_no', 'financial_year', 'project_description', 'department_id',
                DB::raw('SUM(final_budget) as total_allocation'),
                DB::raw('SUM(releases) as total_releases'),
                DB::raw('SUM(expenditure) as total_expenditure')
            )
            ->groupBy('adp_no', 'financial_year', 'project_description', 'department_id')
            ->get();

        // return view('reports.sdg_summary', compact('projects', 'latestDate'));
        return view('reports.sdg_summary', [
            'projects' => $projects,
            'latestDate' => $activeSnapshot->report_date, // Pass the snapshot date to the UI
        ]);
    }

    public function sectorSummary(Request $request)
    {
        // 1. Identify which snapshot to use (Specific Batch or Global Active)
        $batchId = $request->get('batch_id');
        $snapshot = $batchId
            ? \App\Models\SapUpload::findOrFail($batchId)
            : \App\Models\SapUpload::getActiveSnapshot();

        if (! $snapshot) {
            return redirect()->route('sap.upload')->with('error', 'No SAP data found.');
        }

        // 2. Get Funding Stream filter
        $type = $request->get('type', 'all');

        // 3. Define snaphot & stream constraints
        $snapshotConstraint = function ($q) use ($snapshot, $type) {
            $q->where('sap_upload_id', $snapshot->id);
            if ($type === 'adp') {
                $q->notSdg();
            }
            if ($type === 'sdg') {
                $q->isSdg();
            }
        };

        // 4. Fetch Sectors with aggregated project data
        $sectors = \App\Models\Sector::withCount(['sapDumps' => $snapshotConstraint])
            ->withSum(['sapDumps' => $snapshotConstraint], 'final_budget')
            ->withSum(['sapDumps' => $snapshotConstraint], 'releases')
            ->withSum(['sapDumps' => $snapshotConstraint], 'expenditure')
            ->get();

        return view('reports.sector_summary', [
            'sectors' => $sectors,
            'latestDate' => $snapshot->report_date,
            'type' => $type,
            'activeBatch' => $snapshot,
        ]);
    }

    public function sectorDeptAnalysis(Request $request)
    {
        // 1. Identify which snapshot to use
        // If 'batch_id' is in URL, use that. Otherwise, get the 'Active' one from our helper.
        $batchId = $request->get('batch_id');
        $snapshot = $batchId
            ? \App\Models\SapUpload::findOrFail($batchId)
            : \App\Models\SapUpload::getActiveSnapshot();

        if (! $snapshot) {
            return redirect()->route('sap.upload')->with('error', 'No SAP data found. Please upload a dump first.');
        }

        // 2. Get the Funding Stream filter (ADP/SDG/All)
        $type = $request->get('type', 'all');

        // 3. Define a reusable constraint for the nested queries
        // This ensures that Counts, Sums, and the Project List all point to the SAME snapshot
        $snapshotConstraint = function ($query) use ($snapshot, $type) {
            $query->where('sap_upload_id', $snapshot->id);

            if ($type === 'adp') {
                $query->where('adp_no', 'NOT LIKE', 'SG%'); // or $query->notSdg() if scope exists
            } elseif ($type === 'sdg') {
                $query->where('adp_no', 'LIKE', 'SG%'); // or $query->isSdg() if scope exists
            }
        };

        // 4. Fetch the Hierarchy with filtered aggregates
        $sectors = \App\Models\Sector::with(['departments' => function ($query) use ($snapshotConstraint) {
            $query->withCount(['sapDumps' => $snapshotConstraint])
                ->withSum(['sapDumps' => $snapshotConstraint], 'final_budget')
                ->withSum(['sapDumps' => $snapshotConstraint], 'releases')
                ->withSum(['sapDumps' => $snapshotConstraint], 'expenditure')
                  // Also load the actual project records for the "Drill-Down" feature
                ->with(['sapDumps' => $snapshotConstraint]);
        }])->get();

        // 5. Pass data to view
        return view('reports.sector_dept_analysis', [
            'sectors' => $sectors,
            'latestDate' => $snapshot->report_date, // This is what shows in the header
            'type' => $type,
            'activeBatch' => $snapshot, // Helpful if you want to show "Batch #0042" in UI
        ]);
    }
}
