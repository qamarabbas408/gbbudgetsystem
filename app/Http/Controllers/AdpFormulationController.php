<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdpFormulationController extends Controller
{
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
                        'is_targeted' => $throwForward <= 0 ? "true" : "false",
                        'sector_code' => $row['sectorCode'] ?? null,
                        'district_name' => $row['districtCode'] ?? null, // From JS 'districtCode'
                        'halqa_code' => $row['halqa'] ?? null,
                        'head_code' => $row['headCode'] ?? null,
                        'estimated_cost' => $estCost,
                        'exp_upto_june' => $expToDate,
                        'throw_forward' => $throwForward,
                        'original_allocation' => (float) ($row['allocation'] ?? 0),
                        'financial_year' => $fy,
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
