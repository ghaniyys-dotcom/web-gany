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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64);
            $table->string('page_url', 255);
            $table->string('event_type', 50); // visit, cal_click, budget_calc, live_site_click
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            // Add index for fast querying by date and event type
            $table->index(['created_at', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
