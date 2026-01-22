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
        Schema::table('adp_formulations', function (Blueprint $table) {
            // In your migration file
            if (! Schema::hasColumn('adp_formulations', 'approval_date')) {
                $table->text('approval_date')->nullable();
            }
            $table->boolean('is_targeted')->default(false);
        });     

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adp_formulations', function (Blueprint $table) {
            //
            // $table->dropColumn('approval_date');
            // $table->dropColumn('is_targeted');
        });
    }
};
