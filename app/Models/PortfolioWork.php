<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioWork extends Model
{
    protected $fillable = [
        'tag',
        'tag_en',
        'title',
        'body',
        'body_en',
        'image_url',
        'project_url',
        'client',
        'challenge',
        'challenge_en',
        'solution',
        'solution_en',
        'tech_stack',
        'results',
        'results_en',
        'sort_order',
        'is_active',
    ];

    public function getTagAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->tag_en ?: $value;
        }
        return $value;
    }

    public function getBodyAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->body_en ?: $value;
        }
        return $value;
    }

    public function getChallengeAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->challenge_en ?: $value;
        }
        return $value;
    }

    public function getSolutionAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->solution_en ?: $value;
        }
        return $value;
    }

    public function getResultsAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->results_en ?: $value;
        }
        return $value;
    }
}
