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
        Schema::create('project_heads', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., A01270
            $table->string('description');   // e.g., ERE-Pay Others
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_heads');
    }
};
