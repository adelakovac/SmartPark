<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('duration_hours')->default(1)->after('expires_at');
            $table->decimal('total_cost', 8, 2)->default(0)->after('duration_hours');
            $table->decimal('deposit_amount', 8, 2)->default(0)->after('total_cost');
            $table->decimal('deposit_rate', 5, 4)->default(0)->after('deposit_amount');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['duration_hours', 'total_cost', 'deposit_amount', 'deposit_rate']);
        });
    }
};