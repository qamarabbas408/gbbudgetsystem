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
        Schema::create('sap_dumps', function (Blueprint $table) {
            $table->id();

            // 1. Identification Columns
            $table->string('adp_no')->index();
            $table->string('wbs_element')->index();
            $table->text('project_description');
            
            // 2. Financial Columns
            // 18 digits total, 3 after the decimal point
            $table->decimal('final_budget', 18, 3)->default(0); 
            $table->decimal('releases', 18, 3)->default(0);     
            $table->decimal('expenditure', 18, 3)->default(0);  

            // 3. Metadata
            $table->string('financial_year');
            $table->date('as_of_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sap_dumps');
    }
};