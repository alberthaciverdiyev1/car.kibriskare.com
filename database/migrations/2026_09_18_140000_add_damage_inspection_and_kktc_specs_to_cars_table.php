<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Hasar & Ekspertiz
            $table->jsonb('damage_parts')->nullable()->after('condition'); // [part_key => 'original'|'painted'|'local_painted'|'replaced']
            $table->boolean('has_tramer')->default(false)->after('damage_parts')->index();
            $table->decimal('tramer_amount', 12, 2)->nullable()->after('has_tramer');
            $table->string('tramer_currency', 5)->default('GBP')->after('tramer_amount');
            $table->boolean('is_heavy_damaged')->default(false)->after('tramer_currency')->index(); // Pert / Ağır Hasar
            $table->string('inspection_pdf')->nullable()->after('is_heavy_damaged'); // Ekspertiz raporu PDF/resim

            // KKTC Özel Alanları
            $table->string('import_origin', 30)->nullable()->after('plate_type')->index(); // japan, uk, kktc_dealer, europe, turkey, other
            $table->date('road_tax_valid_until')->nullable()->after('import_origin')->index(); // Seyrüsefer son geçerlilik
            $table->date('inspection_valid_until')->nullable()->after('road_tax_valid_until')->index(); // Araç muayene son geçerlilik
            $table->boolean('title_deed_ready')->default(true)->after('inspection_valid_until')->index(); // Koçan / Devre hazır

            // Medya & Video
            $table->string('video_url', 500)->nullable()->after('rejection_reason'); // YouTube / Vimeo / Video linki
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'damage_parts',
                'has_tramer',
                'tramer_amount',
                'tramer_currency',
                'is_heavy_damaged',
                'inspection_pdf',
                'import_origin',
                'road_tax_valid_until',
                'inspection_valid_until',
                'title_deed_ready',
                'video_url',
            ]);
        });
    }
};
