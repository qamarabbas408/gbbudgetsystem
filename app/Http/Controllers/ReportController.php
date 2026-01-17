<?php 
namespace App\Http\Controllers;

use App\Models\SapDump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function adpSummary(Request $request)
    {
        // Get the latest upload date to show current data by default
        $latestDate = SapDump::max('as_of_date');
        
        $query  = SapDump::query();

        // Optional: Filter by Financial Year if selected
        if ($request->has('fy')) {
            $query->where('financial_year', $request->fy);
        }
        
        // Group by ADP No and Financial Year to get project totals
        $projects = $query->select(
                'adp_no',
                'financial_year',
                'project_description',
                DB::raw('SUM(final_budget) as total_allocation'),
                DB::raw('SUM(releases) as total_releases'),
                DB::raw('SUM(expenditure) as total_expenditure')
            )
            ->where('as_of_date', $latestDate) // Only show the most recent snapshot
            ->groupBy('adp_no', 'financial_year', 'project_description')
            ->get();

        return view('reports.adp_summary', compact('projects', 'latestDate'));
    }
}