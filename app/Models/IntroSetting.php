<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntroSetting extends Model
{
    protected $fillable = [
        'is_enabled', 'greeting', 'greeting_en', 'name', 'roles', 'tagline', 'tagline_en',
        'cta_text', 'cta_text_en', 'availability_enabled', 'is_available',
        'availability_text', 'availability_text_en', 'expertise_tickers',
    ];

    protected $casts = [
        'is_enabled'           => 'boolean',
        'availability_enabled' => 'boolean',
        'is_available'         => 'boolean',
        'roles'                => 'array',
        'expertise_tickers'    => 'array',
    ];

    public function getGreetingAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->greeting_en ?: $value;
        }
        return $value;
    }

    public function getTaglineAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->tagline_en ?: $value;
        }
        return $value;
    }

    public function getCtaTextAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->cta_text_en ?: $value;
        }
        return $value;
    }

    public function getAvailabilityTextAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->availability_text_en ?: $value;
        }
        return $value;
    }

    public static function current(): self
    {
        return static::first() ?? static::create(static::defaults());
    }

    public static function defaults(): array
    {
        return [
            'is_enabled'           => true,
            'greeting'             => 'welcome',
            'greeting_en'          => 'welcome',
            'name'                 => "Gany's Portofolio",
            'roles'                => ['Full-Stack Developer', 'UI/UX Enthusiast', 'Laravel Engineer', 'Software Engineer'],
            'tagline'              => 'Build premium and functional digital products.',
            'tagline_en'           => 'Build premium and functional digital products.',
            'cta_text'             => 'Lihat Karya Gua →',
            'cta_text_en'          => 'View My Work →',
            'availability_enabled' => true,
            'is_available'         => true,
            'availability_text'    => 'Available for new projects',
            'availability_text_en' => 'Available for new projects',
            'expertise_tickers'    => [
                'Building scalable APIs',
                'Crafting elegant interfaces',
                'Designing premium UX',
                'Shipping production-ready apps',
            ],
        ];
    }
}
