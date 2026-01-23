<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adb_dump', function (Blueprint $table) {
         $table->decimal('allocated_faid', 18, 3)->default(0)->after('original_allocation'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adb_dump', function (Blueprint $table) {
            $table->dropColumn('allocated_faid');
        });
    }
};
