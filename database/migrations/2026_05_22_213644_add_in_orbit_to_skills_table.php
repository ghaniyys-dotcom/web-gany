<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->boolean('in_orbit')->default(false)->after('is_active');
        });

        // Ensure default orbit skills exist
        $orbitSkills = [
            ['name' => 'React', 'level' => 90, 'years' => 3, 'category' => 'Frontend', 'color' => '#61dafb', 'sort_order' => 13, 'in_orbit' => true, 'is_active' => true],
            ['name' => 'Three.js', 'level' => 80, 'years' => 2, 'category' => 'Frontend', 'color' => '#ffffff', 'sort_order' => 14, 'in_orbit' => true, 'is_active' => true],
            ['name' => 'Figma', 'level' => 85, 'years' => 3, 'category' => 'Design', 'color' => '#f24e1e', 'sort_order' => 15, 'in_orbit' => true, 'is_active' => true],
        ];

        foreach ($orbitSkills as $os) {
            DB::table('skills')->updateOrInsert(
                ['name' => $os['name']],
                $os
            );
        }

        // Set in_orbit = true for existing skills in our 8 logo set
        DB::table('skills')
            ->whereIn('name', ['Laravel', 'PHP', 'MySQL', 'JavaScript', 'CSS / Tailwind'])
            ->update(['in_orbit' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('in_orbit');
        });
    }
};

