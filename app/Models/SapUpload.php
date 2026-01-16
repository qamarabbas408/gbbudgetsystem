<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SapUpload extends Model
{
    protected $fillable = ['financial_year', 'report_date', 'file_name'];
}
