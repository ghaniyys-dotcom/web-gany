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
        Schema::create('portfolio_works', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->string('title');
            $table->text('body');
            $table->string('image_url')->nullable();
            $table->string('project_url')->nullable();
            $table->string('client')->nullable();
            $table->text('challenge')->nullable();
            $table->text('solution')->nullable();
            $table->string('tech_stack')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seamless data migration to move existing works to the portfolio_works table
        try {
            $siteSetting = \Illuminate\Support\Facades\DB::table('site_settings')->first();
            if ($siteSetting && !empty($siteSetting->works)) {
                $works = json_decode($siteSetting->works, true);
                if (is_array($works)) {
                    foreach ($works as $index => $w) {
                        \Illuminate\Support\Facades\DB::table('portfolio_works')->insert([
                            'tag' => $w['tag'] ?? '',
                            'title' => $w['title'] ?? '',
                            'body' => $w['body'] ?? '',
                            'image_url' => $w['image_url'] ?? null,
                            'project_url' => $w['project_url'] ?? null,
                            'client' => $w['client'] ?? null,
                            'challenge' => $w['challenge'] ?? null,
                            'solution' => $w['solution'] ?? null,
                            'tech_stack' => $w['tech_stack'] ?? null,
                            'sort_order' => $index,
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('PortfolioWork migration data porting failed: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_works');
    }
};
