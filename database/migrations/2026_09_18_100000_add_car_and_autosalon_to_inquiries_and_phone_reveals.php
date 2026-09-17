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
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'car_id')) {
                $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            }
            if (!Schema::hasColumn('inquiries', 'autosalon_id')) {
                $table->foreignId('autosalon_id')->nullable()->constrained('autosalons')->nullOnDelete();
            }
        });

        if (Schema::hasTable('listing_phone_reveals')) {
            Schema::table('listing_phone_reveals', function (Blueprint $table) {
                if (Schema::hasColumn('listing_phone_reveals', 'listing_id')) {
                    $table->unsignedBigInteger('listing_id')->nullable()->change();
                }
                if (!Schema::hasColumn('listing_phone_reveals', 'car_id')) {
                    $table->foreignId('car_id')->nullable()->constrained('cars')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('listing_phone_reveals', 'autosalon_id')) {
                    $table->foreignId('autosalon_id')->nullable()->constrained('autosalons')->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'car_id')) {
                $table->dropForeign(['car_id']);
                $table->dropColumn('car_id');
            }
            if (Schema::hasColumn('inquiries', 'autosalon_id')) {
                $table->dropForeign(['autosalon_id']);
                $table->dropColumn('autosalon_id');
            }
        });

        if (Schema::hasTable('listing_phone_reveals')) {
            Schema::table('listing_phone_reveals', function (Blueprint $table) {
                if (Schema::hasColumn('listing_phone_reveals', 'car_id')) {
                    $table->dropForeign(['car_id']);
                    $table->dropColumn('car_id');
                }
                if (Schema::hasColumn('listing_phone_reveals', 'autosalon_id')) {
                    $table->dropForeign(['autosalon_id']);
                    $table->dropColumn('autosalon_id');
                }
            });
        }
    }
};
