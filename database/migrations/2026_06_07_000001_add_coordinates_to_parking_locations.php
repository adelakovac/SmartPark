<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parking_locations', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('description');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->decimal('hourly_rate', 8, 2)->default(2.00)->after('longitude');
            $table->string('opening_hours')->default('00:00 - 24:00')->after('hourly_rate');
        });
    }

    public function down(): void
    {
        Schema::table('parking_locations', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'hourly_rate', 'opening_hours']);
        });
    }
};