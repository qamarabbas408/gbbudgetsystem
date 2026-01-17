<?php

namespace App\Http\Controllers;

use App\Models\SapDump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function adpSummary(Request $request)
    {
        $latestDate = SapDump::max('as_of_date');

        // We use 'with' to eagerly load Department and Sector (prevents slow loading)
        $projects = SapDump::with(['department.sector'])
            ->notSdg()
            ->select('adp_no', 'financial_year', 'project_description', 'department_id',
                DB::raw('SUM(final_budget) as total_allocation'),
                DB::raw('SUM(releases) as total_releases'),
                DB::raw('SUM(expenditure) as total_expenditure')
            )
            ->where('as_of_date', $latestDate)
            ->groupBy('adp_no', 'financial_year', 'project_description', 'department_id')
            ->get();

        return view('reports.adp_summary', compact('projects', 'latestDate'));
    }

    public function sdgSummary(Request $request)
    {
        $latestDate = SapDump::max('as_of_date');

        $projects = SapDump::with(['department.sector'])
            ->isSdg() // <--- Filter for ONLY SG projects
            ->select('adp_no', 'financial_year', 'project_description', 'department_id',
                DB::raw('SUM(final_budget) as total_allocation'),
                DB::raw('SUM(releases) as total_releases'),
                DB::raw('SUM(expenditure) as total_expenditure')
            )
            ->where('as_of_date', $latestDate)
            ->groupBy('adp_no', 'financial_year', 'project_description', 'department_id')
            ->get();

        return view('reports.sdg_summary', compact('projects', 'latestDate'));
    }

    public function sectorSummary()
    {
        $latestDate = \App\Models\SapDump::max('as_of_date');

        // Fetch sectors with calculated sums of their projects
        $sectors = \App\Models\Sector::withCount(['sapDumps' => function ($q) use ($latestDate) {
            $q->where('as_of_date', $latestDate);
        }])
            ->withSum(['sapDumps' => function ($q) use ($latestDate) {
                $q->where('as_of_date', $latestDate);
            }], 'final_budget')
            ->withSum(['sapDumps' => function ($q) use ($latestDate) {
                $q->where('as_of_date', $latestDate);
            }], 'releases')
            ->withSum(['sapDumps' => function ($q) use ($latestDate) {
                $q->where('as_of_date', $latestDate);
            }], 'expenditure')
            ->get();

        return view('reports.sector_summary', compact('sectors', 'latestDate'));
    }

   public function sectorDeptAnalysis(Request $request)
{
    $latestDate = \App\Models\SapDump::max('as_of_date');
    $type = $request->get('type', 'all'); // Default to showing everything

    $sectors = \App\Models\Sector::with(['departments' => function($query) use ($latestDate, $type) {
        $query->withCount(['sapDumps' => function($q) use ($latestDate, $type) {
            $q->where('as_of_date', $latestDate);
            // Apply filtering logic
            if ($type === 'adp') $q->notSdg();
            if ($type === 'sdg') $q->isSdg();
        }])
        ->withSum(['sapDumps' => function($q) use ($latestDate, $type) {
            $q->where('as_of_date', $latestDate);
            if ($type === 'adp') $q->notSdg();
            if ($type === 'sdg') $q->isSdg();
        }], 'final_budget')
        ->withSum(['sapDumps' => function($q) use ($latestDate, $type) {
            $q->where('as_of_date', $latestDate);
            if ($type === 'adp') $q->notSdg();
            if ($type === 'sdg') $q->isSdg();
        }], 'releases')
        ->withSum(['sapDumps' => function($q) use ($latestDate, $type) {
            $q->where('as_of_date', $latestDate);
            if ($type === 'adp') $q->notSdg();
            if ($type === 'sdg') $q->isSdg();
        }], 'expenditure');
    }])->get();

    return view('reports.sector_dept_analysis', compact('sectors', 'latestDate', 'type'));
}
}
