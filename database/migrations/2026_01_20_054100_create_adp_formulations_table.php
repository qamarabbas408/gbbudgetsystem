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
        Schema::create('adp_formulations', function (Blueprint $table) {
            $table->id();

            // 1. Identity & Classification
            $table->string('adp_no')->unique()->index();
            $table->string('old_adp_no')->nullable();
            $table->text('scheme_name');
            $table->string('sector_code')->nullable();
            $table->string('district_name')->nullable();
            $table->string('halqa_code')->nullable(); // MLA Constituency
            $table->string('head_code')->nullable();  // e.g. A12102

            // 2. Status & Approval
            $table->boolean('is_approved')->default(false);
            $table->date('approval_date')->nullable();

            // 3. Planning Figures (From ADP Excel)
            $table->decimal('estimated_cost', 18, 2)->default(0);
            $table->decimal('exp_upto_june', 18, 2)->default(0);
            $table->decimal('throw_forward', 18, 2)->default(0);
            $table->decimal('original_allocation', 18, 2)->default(0);

            // 4. Live Financials (From SAP Sync or Manual Entry)
            $table->decimal('final_budget', 18, 2)->default(0);    // Re-allocation
            $table->decimal('total_releases', 18, 2)->default(0);
            $table->decimal('total_expenditure', 18, 2)->default(0);

            // 5. Metadata
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adp_formulations');
    }
};
