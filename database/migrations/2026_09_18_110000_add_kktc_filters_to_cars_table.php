<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vehicle_type', 30)->default('car')->after('autosalon_id')->index();
            $table->string('plate_type', 30)->default('kktc')->after('condition')->index();
            $table->boolean('has_warranty')->default(false)->after('is_barter_available')->index();
            $table->boolean('is_negotiable')->default(false)->after('has_warranty')->index();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_type',
                'plate_type',
                'has_warranty',
                'is_negotiable',
            ]);
        });
    }
};
