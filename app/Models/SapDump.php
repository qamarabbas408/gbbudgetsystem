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
        'as_of_date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Add these inside the class
    public function scopeIsSdg($query)
    {
        return $query->where('adp_no', 'like', 'SG%');
    }

    public function scopeNotSdg($query)
    {
        // Exclude anything starting with SG
        return $query->where('adp_no', 'like', 'A%')->whereNot('adp_no', 'like', 'AB%');
    }

    public function sapUpload()
    {
        return $this->belongsTo(SapUpload::class, 'sap_upload_id');
    }
}
