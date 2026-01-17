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
}