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
        Schema::table('sap_dumps', function (Blueprint $table) {
            // This adds the link. If an upload record is deleted, 
            // all related dump rows will also be deleted (cascade).
            $table->foreignId('sap_upload_id')
                  ->after('id') // Position it at the top
                  ->constrained('sap_uploads')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sap_dumps', function (Blueprint $table) {
            // Drop the foreign key first, then the column
            $table->dropForeign(['sap_upload_id']);
            $table->dropColumn('sap_upload_id');
        });
    }
};
