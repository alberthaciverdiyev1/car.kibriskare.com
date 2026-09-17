<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_body_types', function (Blueprint $table) {
            $table->jsonb('applicable_types')->nullable()->after('icon');
        });

        Schema::table('car_brands', function (Blueprint $table) {
            $table->jsonb('applicable_types')->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('car_body_types', function (Blueprint $table) {
            $table->dropColumn('applicable_types');
        });

        Schema::table('car_brands', function (Blueprint $table) {
            $table->dropColumn('applicable_types');
        });
    }
};
