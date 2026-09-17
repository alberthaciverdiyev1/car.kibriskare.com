<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_body_types', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name'); // {"tr": "Sedan", "en": "Sedan", "ru": "Седан"}
            $table->string('slug', 80)->unique();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('car_features', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name'); // {"tr": "Deri Koltuk", "en": "Leather seats"}
            $table->string('slug', 100)->unique();
            $table->string('category', 50)->default('comfort')->index(); // comfort, safety, multimedia, exterior, interior
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_features');
        Schema::dropIfExists('car_body_types');
    }
};
