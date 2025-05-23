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
        Schema::create('roles', function (Blueprint $table) { // Fixed table name
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description', 1000)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Keep if you want to allow soft deletion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles'); // Updated to match correct table name
    }
};
