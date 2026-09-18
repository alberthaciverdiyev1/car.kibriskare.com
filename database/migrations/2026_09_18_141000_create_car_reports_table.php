<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason', 100); // fake_ad, wrong_info, already_sold, scam_deposit, inappropriate, other
            $table->text('description')->nullable();
            $table->string('contact_info', 150)->nullable(); // phone or email
            $table->string('status', 30)->default('pending')->index(); // pending, reviewed, dismissed, resolved
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['car_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_reports');
    }
};
