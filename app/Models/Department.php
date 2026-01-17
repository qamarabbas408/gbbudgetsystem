<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }


     /**
     * NEW: Link to Projects (SapDumps)
     * This allows the report to sum up projects for each department
     */
    public function sapDumps()
    {
        return $this->hasMany(SapDump::class, 'department_id');
    }
}
