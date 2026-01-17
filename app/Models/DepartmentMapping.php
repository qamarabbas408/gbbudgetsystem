<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentMapping extends Model
{
protected $fillable = ['department_id', 'start_adp', 'end_adp', 'type'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
