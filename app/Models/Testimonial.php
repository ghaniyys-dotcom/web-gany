<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'role', 'company', 'quote', 'quote_en', 'rating', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'rating' => 'integer'];

    public function getQuoteAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->quote_en ?: $value;
        }
        return $value;
    }
}
