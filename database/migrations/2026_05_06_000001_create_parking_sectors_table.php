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
        Schema::create('parking_sectors', function (Blueprint $col) {
            $col->id();
            $col->foreignId('parking_area_id')->constrained()->onDelete('cascade');
            $col->string('name');
            $col->string('code')->nullable(); // e.g., Sector A, Sector B
            $col->text('description')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sectors');
    }
};
