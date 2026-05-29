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
            $table->string('tagline_en')->nullable()->after('tagline');
            $table->text('hero_title_en')->nullable()->after('hero_title');
            $table->text('hero_subtitle_en')->nullable()->after('hero_subtitle');
            $table->string('primary_cta_en')->nullable()->after('primary_cta');
            $table->string('secondary_cta_en')->nullable()->after('secondary_cta');
            $table->json('services_en')->nullable()->after('services');
            $table->json('stats_en')->nullable()->after('stats');
        });

        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->string('tag_en')->nullable()->after('tag');
            $table->text('body_en')->nullable()->after('body');
            $table->text('challenge_en')->nullable()->after('challenge');
            $table->text('solution_en')->nullable()->after('solution');
            $table->text('results_en')->nullable()->after('results');
        });

        Schema::table('founder_profiles', function (Blueprint $table) {
            $table->string('eyebrow_en')->nullable()->after('eyebrow');
            $table->string('heading_en')->nullable()->after('heading');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('intro_settings', function (Blueprint $table) {
            $table->string('greeting_en')->nullable()->after('greeting');
            $table->string('tagline_en')->nullable()->after('tagline');
            $table->string('cta_text_en')->nullable()->after('cta_text');
            $table->string('availability_text_en')->nullable()->after('availability_text');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question_en')->nullable()->after('question');
            $table->text('answer_en')->nullable()->after('answer');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->text('quote_en')->nullable()->after('quote');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['tagline_en', 'hero_title_en', 'hero_subtitle_en', 'primary_cta_en', 'secondary_cta_en', 'services_en', 'stats_en']);
        });

        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->dropColumn(['tag_en', 'body_en', 'challenge_en', 'solution_en', 'results_en']);
        });

        Schema::table('founder_profiles', function (Blueprint $table) {
            $table->dropColumn(['eyebrow_en', 'heading_en', 'description_en']);
        });

        Schema::table('intro_settings', function (Blueprint $table) {
            $table->dropColumn(['greeting_en', 'tagline_en', 'cta_text_en', 'availability_text_en']);
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question_en', 'answer_en']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('quote_en');
        });
    }
};
