<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->change();
            $table->foreignId('car_id')->nullable()->after('property_id')->constrained('cars')->cascadeOnDelete();
            $table->index(['user_id', 'car_id']);
            $table->index(['session_id', 'car_id']);
        });

        Schema::table('compares', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->change();
            $table->foreignId('car_id')->nullable()->after('property_id')->constrained('cars')->cascadeOnDelete();
            $table->index(['user_id', 'car_id']);
            $table->index(['session_id', 'car_id']);
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropColumn('car_id');
        });

        Schema::table('compares', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropColumn('car_id');
        });
    }
};
