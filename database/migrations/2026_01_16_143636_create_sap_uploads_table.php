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
        Schema::create('sap_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year');
            $table->date('report_date'); // User selects this in the UI
            $table->string('file_name');
            $table->foreignId('user_id')->nullable();
            $table->timestamps(); // This tracks the actual upload time (e.g., 10:00 AM vs 2:00 PM)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sap_uploads');
    }
};
