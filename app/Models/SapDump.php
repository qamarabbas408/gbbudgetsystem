<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SapDump extends Model
{
   protected $fillable = [
    'adp_no', 
    'wbs_element', 
    'project_description', 
    'final_budget', 
    'releases', 
    'expenditure', 
    'financial_year', 
    'as_of_date'
];

public function department()
{
    return $this->belongsTo(Department::class);
}
}