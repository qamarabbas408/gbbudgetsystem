<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renames the table
        Schema::rename('adp_formulations', 'adb_dump');
    }

    public function down(): void
    {
        // Reverts back if you ever rollback
        Schema::rename('adb_dump', 'adp_formulations');
    }
};
