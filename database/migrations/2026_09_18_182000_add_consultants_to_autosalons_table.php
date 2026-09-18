<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autosalons', function (Blueprint $table) {
            $table->jsonb('consultants')->nullable()->after('working_hours');
        });
    }

    public function down(): void
    {
        Schema::table('autosalons', function (Blueprint $table) {
            $table->dropColumn('consultants');
        });
    }
};
