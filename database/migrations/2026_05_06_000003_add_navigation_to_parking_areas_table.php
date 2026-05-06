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
        Schema::table('parking_areas', function (Blueprint $table) {
            $table->string('map_image')->nullable()->after('image_path');
            $table->decimal('latitude', 10, 8)->nullable()->after('map_image');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('google_maps_link')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_areas', function (Blueprint $table) {
            $table->dropColumn(['map_image', 'latitude', 'longitude', 'google_maps_link']);
        });
    }
};
