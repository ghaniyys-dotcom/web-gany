<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'level', 'years', 'category', 'color', 'sort_order', 'is_active', 'in_orbit'];

    protected $casts = [
        'is_active' => 'boolean',
        'in_orbit' => 'boolean',
    ];


    public static function defaults(): array
    {
        return [
            ['name' => 'Laravel',       'level' => 95, 'years' => 4, 'category' => 'Backend',  'color' => '#ff2d20', 'sort_order' => 1],
            ['name' => 'PHP',           'level' => 92, 'years' => 5, 'category' => 'Backend',  'color' => '#8993be', 'sort_order' => 2],
            ['name' => 'Vue.js',        'level' => 85, 'years' => 3, 'category' => 'Frontend', 'color' => '#42b883', 'sort_order' => 3],
            ['name' => 'JavaScript',    'level' => 88, 'years' => 5, 'category' => 'Frontend', 'color' => '#f7df1e', 'sort_order' => 4],
            ['name' => 'MySQL',         'level' => 87, 'years' => 4, 'category' => 'Database', 'color' => '#4479a1', 'sort_order' => 5],
            ['name' => 'PostgreSQL',    'level' => 80, 'years' => 2, 'category' => 'Database', 'color' => '#336791', 'sort_order' => 6],
            ['name' => 'REST APIs',     'level' => 93, 'years' => 4, 'category' => 'Backend',  'color' => '#6246ea', 'sort_order' => 7],
            ['name' => 'Docker',        'level' => 75, 'years' => 2, 'category' => 'DevOps',   'color' => '#2496ed', 'sort_order' => 8],
            ['name' => 'CSS / Tailwind','level' => 90, 'years' => 4, 'category' => 'Frontend', 'color' => '#38bdf8', 'sort_order' => 9],
            ['name' => 'Git',           'level' => 92, 'years' => 5, 'category' => 'DevOps',   'color' => '#f05032', 'sort_order' => 10],
            ['name' => 'UI / UX',       'level' => 82, 'years' => 3, 'category' => 'Design',   'color' => '#e02f83', 'sort_order' => 11],
            ['name' => 'Livewire',      'level' => 88, 'years' => 2, 'category' => 'Backend',  'color' => '#fb70a9', 'sort_order' => 12],
        ];
    }
}
