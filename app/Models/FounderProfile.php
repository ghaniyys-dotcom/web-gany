<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FounderProfile extends Model
{
    protected $fillable = [
        'eyebrow',
        'eyebrow_en',
        'heading',
        'heading_en',
        'description',
        'description_en',
        'photo_path',
        'signature_path',
    ];

    public function getEyebrowAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->eyebrow_en ?: $value;
        }
        return $value;
    }

    public function getHeadingAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->heading_en ?: $value;
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->description_en ?: $value;
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
            'eyebrow'        => 'MEET THE FOUNDER',
            'eyebrow_en'     => 'MEET THE FOUNDER',
            'heading'        => "Hi, I'm Gany.",
            'heading_en'     => "Hi, I'm Gany.",
            'description'    => "Gua adalah software engineer yang berdedikasi penuh untuk merancang dan membangun produk digital kelas premium. Di Gany Labs, gua percaya bahwa website bukan sekadar kode dan interface fungsional biasa. Setiap detail visual harus dirawat dengan taste seni tinggi agar memancarkan kesan eksklusif dan mahal. Melalui strategi, desain estetik, serta arsitektur kode modern, gua siap membantu lo menaikkan nilai brand di mata publik dan memikat klien-klien terbaik.",
            'description_en' => "I am a dedicated software engineer specializing in designing and building premium digital products. At Gany Labs, I believe a website is not just code and a functional interface. Every visual detail must be crafted with high artistic taste to radiate an exclusive and premium feel. Through strategy, aesthetic design, and modern code architecture, I am ready to help you elevate your brand value and attract the best clients.",
            'photo_path'     => null,
            'signature_path' => null,
        ];
    }
}
