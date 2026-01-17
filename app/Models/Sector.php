<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function sapDumps()
    {
        // This allows us to get all project rows belonging to this sector
        return $this->hasManyThrough(SapDump::class, Department::class);
    }
}
