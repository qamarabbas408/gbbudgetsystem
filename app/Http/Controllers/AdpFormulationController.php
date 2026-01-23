<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdpFormulationController extends Controller
{
    // public function index()
    // {
    //     // 1. Get the ID of the Active Snapshot
    //     $activeSnapshot = \App\Models\SapUpload::getActiveSnapshot();
    //     $activeId = $activeSnapshot ? $activeSnapshot->id : 0;

    //     // 2. Fetch schemes but filter the SAP relationship to ONLY the active batch
    //     $schemes = \App\Models\AdpFormulation::with(['sapDumps' => function ($query) use ($activeId) {
    //         $query->where('sap_upload_id', $activeId);
    //     }])->get();

    //     return view('adp.formulation', compact('schemes', 'activeSnapshot'));
    // }

    public function index(Request $request)
    {
        // 1. Get the Active SAP Snapshot ID
        $activeSnapshot = \App\Models\SapUpload::getActiveSnapshot();
        $activeId = $activeSnapshot ? $activeSnapshot->id : 0;

        // 2. Start the Query
        $query = \App\Models\AdpFormulation::query();

        // 3. APPLY FILTERS
        // Search (Scheme Name or ADP#)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('scheme_name', 'like', '%'.$request->search.'%')
                    ->orWhere('adp_no', 'like', '%'.$request->search.'%');
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('approval_date', '!=', 'un-app');
            } elseif ($request->status === 'unapproved') {
                $query->where('approval_date', 'un-app');
            }
        }

        // District Filter
        if ($request->filled('district')) {
            $query->where('district_name', $request->district);
        }

        // Sector Filter
        if ($request->filled('sector')) {
            $query->where('sector_code', $request->sector);
        }

        // 4. EAGER AGGREGATION (High Performance)
        // This adds virtual columns: sap_final_budget, sap_releases, sap_exp
        $schemes = $query->withSum(['sapDumps as sap_final_budget' => function ($q) use ($activeId) {
            $q->where('sap_upload_id', $activeId);
        }], 'final_budget')
            ->withSum(['sapDumps as sap_releases' => function ($q) use ($activeId) {
                $q->where('sap_upload_id', $activeId);
            }], 'releases')
            ->withSum(['sapDumps as sap_expenditure' => function ($q) use ($activeId) {
                $q->where('sap_upload_id', $activeId);
            }], 'expenditure')
            ->orderBy('adp_no', 'asc')
            ->paginate($request->get('per_page', 50)) // Dynamic pagination
            ->withQueryString(); // Keeps your filters active when you click "Next"

        // 5. Get Unique Values for Dropdowns (Optimized)
        $districts = \App\Models\AdpFormulation::distinct()->pluck('district_name')->filter();
        $sectors = \App\Models\AdpFormulation::distinct()->pluck('sector_code')->filter();

        // 6. Calculate Stats for the top cards (on full filtered set, not just page)
        $stats = [
            'total' => $query->count(),
            'approved' => (clone $query)->where('approval_date', '!=', 'un-app')->count(),
            'unapproved' => (clone $query)->where('approval_date', 'un-app')->count(),
            'allocation' => $query->sum('original_allocation'),
        ];

        return view('adp.formulation', compact('schemes', 'districts', 'sectors', 'stats', 'activeSnapshot'));
    }

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'data' => 'required|array',
            'financial_year' => 'required|string',
        ]);

        $data = $request->input('data');
        $fy = $request->input('financial_year');

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                // Use keys from your refined JavaScript (e.g., adpCode, estimatedCost)
                $estCost = (float) ($row['estimatedCost'] ?? 0);
                $expToDate = (float) ($row['expenditureToDate'] ?? 0);

                // Recalculate liability for database integrity
                $throwForward = $estCost - $expToDate;

                \App\Models\AdpFormulation::updateOrCreate(
                    ['adp_no' => trim($row['adpCode'])], // Unique Bridge ID
                    [
                        'scheme_name' => trim($row['description']),
                        'approval_date' => $row['approvalDate'] ?? null,
                        'is_targeted' => $throwForward <= 0 ? 'true' : 'false',
                        'sector_code' => $row['sectorCode'] ?? null,
                        'district_name' => $row['districtCode'] ?? null, // From JS 'districtCode'
                        'halqa_code' => $row['halqa'] ?? null,
                        'head_code' => $row['headCode'] ?? null,
                        'estimated_cost' => $estCost,
                        'exp_upto_june' => $expToDate,
                        'throw_forward' => $throwForward,
                        'original_allocation' => (float) ($row['allocatedAmount'] ?? 0),
                        'financial_year' => $fy,
                        'allocated_faid' => $row['allocationFaid'],
                        // Note: accounting columns stay at 0 until SAP sync
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => count($data).' ADP schemes have been synchronized successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Sync Error: '.$e->getMessage(),
            ], 500);
        }
    }
}
