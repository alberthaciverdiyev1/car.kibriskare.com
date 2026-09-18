<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->decimal('old_price_gbp', 12, 2)->nullable()->after('price_usd');
            $table->decimal('old_price_try', 12, 2)->nullable()->after('old_price_gbp');
            $table->decimal('old_price_eur', 12, 2)->nullable()->after('old_price_try');
            $table->decimal('old_price_usd', 12, 2)->nullable()->after('old_price_eur');
            $table->timestamp('price_dropped_at')->nullable()->after('old_price_usd')->index();
            $table->boolean('is_plate_masked')->default(false)->after('plate_type')->index();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'old_price_gbp',
                'old_price_try',
                'old_price_eur',
                'old_price_usd',
                'price_dropped_at',
                'is_plate_masked',
            ]);
        });
    }
};
