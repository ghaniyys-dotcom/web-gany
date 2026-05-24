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
        Schema::create('founder_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow')->default('MEET THE FOUNDER');
            $table->string('heading')->default("Hi, I'm Gany.");
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('founder_profiles');
    }
};
