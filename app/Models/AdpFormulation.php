<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdpFormulation extends Model
{
     protected $fillable = [
        'adp_no', 'old_adp_no', 'scheme_name', 'sector_code', 
        'district_name', 'halqa_code', 'head_code', 
        'is_approved', 'approval_date', 
        'estimated_cost', 'exp_upto_june', 'throw_forward', 'original_allocation',
        'final_budget', 'total_releases', 'total_expenditure', 'remarks'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approval_date' => 'date',
    ];
}
