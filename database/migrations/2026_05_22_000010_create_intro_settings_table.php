<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intro_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('greeting')->default('Halo 👋');
            $table->string('name')->default('Gany');
            $table->json('roles')->nullable();
            $table->string('tagline')->nullable();
            $table->string('cta_text')->default('Lihat Karya Gua →');
            $table->boolean('availability_enabled')->default(true);
            $table->boolean('is_available')->default(true);
            $table->string('availability_text')->default('Available for new projects');
            $table->json('expertise_tickers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intro_settings');
    }
};
