<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SapUpload extends Model
{
    // Make sure these match the columns in your sap_uploads table
    protected $fillable = ['financial_year', 'report_date', 'file_name', 'is_active'];

    // Ensure the date is treated as a Carbon object for formatting in Blade
    protected $casts = [
        'report_date' => 'date',
    ];

    /**
     * Relationship: One Upload has many Project Rows (Dumps)
     */
    public function sapDumps()
    {
        // Link to the child table using 'sap_upload_id' as the foreign key
        return $this->hasMany(SapDump::class, 'sap_upload_id');
    }
}
