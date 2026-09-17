<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('autosalon_id')->nullable()->constrained('autosalons')->nullOnDelete();
            $table->foreignId('brand_id')->constrained('car_brands')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('car_models')->cascadeOnDelete();
            $table->foreignId('body_type_id')->nullable()->constrained('car_body_types')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();

            $table->jsonb('title')->nullable();
            $table->string('slug', 255)->unique();
            $table->jsonb('description')->nullable();

            // Deal type: sale (satılık), rent_daily (günlük kiralık), rent_monthly (aylık kiralık)
            $table->string('deal_type', 30)->default('sale')->index();

            // Multi-currency price storage
            $table->decimal('price_gbp', 12, 2)->nullable()->index();
            $table->decimal('price_try', 14, 2)->nullable()->index();
            $table->decimal('price_eur', 12, 2)->nullable()->index();
            $table->decimal('price_usd', 12, 2)->nullable()->index();
            $table->string('main_currency', 5)->default('GBP')->index();

            // Technical Specifications
            $table->smallInteger('year')->unsigned()->index();
            $table->integer('mileage')->unsigned()->index();
            $table->string('mileage_unit', 10)->default('km'); // km, mi
            $table->smallInteger('engine_volume')->unsigned()->nullable()->index(); // in cc e.g. 1995
            $table->smallInteger('engine_power')->unsigned()->nullable()->index(); // in hp e.g. 190
            $table->string('fuel_type', 30)->default('petrol')->index(); // petrol, diesel, hybrid, plug_in_hybrid, electric, lpg
            $table->string('transmission', 30)->default('automatic')->index(); // automatic, manual, robot, cvt
            $table->string('drivetrain', 30)->nullable()->index(); // front_wheel, rear_wheel, all_wheel
            $table->string('steering_wheel', 10)->default('right')->index(); // right (Sağ), left (Sol)
            $table->string('color', 50)->nullable()->index();
            $table->boolean('is_metallic')->default(false);
            $table->tinyInteger('doors')->unsigned()->default(4);
            $table->tinyInteger('seats')->unsigned()->default(5);

            // Condition & Commercial status
            $table->string('condition', 30)->default('used')->index(); // new, used, damaged, for_parts
            $table->boolean('is_customs_cleared')->default(true)->index(); // KKTC Plakalı / Gümrük ödenmiş
            $table->boolean('is_credit_available')->default(false)->index(); // Kredi / Taksit imkanı
            $table->boolean('is_barter_available')->default(false)->index(); // Takas / Barter
            $table->string('vin', 50)->nullable();

            // Contact Info
            $table->string('seller_type', 20)->default('owner')->index(); // owner (sahibinden), dealer (galeri)
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_whatsapp', 50)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            // Status & Flags
            $table->string('status', 30)->default('active')->index(); // pending, active, rejected, sold, inactive
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_vip')->default(false)->index();
            $table->boolean('is_premium')->default(false)->index();
            $table->boolean('is_urgent')->default(false)->index();

            // Stats
            $table->integer('view_count')->default(0);
            $table->integer('phone_view_count')->default(0);
            $table->integer('favorite_count')->default(0);

            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('expired_at')->nullable()->index();
            $table->timestamps();

            // Performance composite indexes
            $table->index(['status', 'deal_type', 'is_vip', 'created_at']);
            $table->index(['brand_id', 'model_id', 'status']);
            $table->index(['status', 'year', 'price_gbp']);
        });

        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_main')->default(false)->index();
            $table->smallInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->index(['car_id', 'sort_order']);
        });

        Schema::create('car_feature_car', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('car_feature_id')->constrained('car_features')->cascadeOnDelete();

            $table->unique(['car_id', 'car_feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_feature_car');
        Schema::dropIfExists('car_images');
        Schema::dropIfExists('cars');
    }
};
