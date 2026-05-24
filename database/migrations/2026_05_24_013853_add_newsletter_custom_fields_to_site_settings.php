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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('newsletter_title')->nullable();
            $table->string('newsletter_title_en')->nullable();
            $table->text('newsletter_desc')->nullable();
            $table->text('newsletter_desc_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['newsletter_title', 'newsletter_title_en', 'newsletter_desc', 'newsletter_desc_en']);
        });
    }
};
