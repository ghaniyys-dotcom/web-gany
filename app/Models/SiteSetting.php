<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class SiteSetting extends Model
{
    protected $fillable = [
        'brand_name', 'logo_initials', 'tagline', 'tagline_en', 'hero_title', 'hero_title_en',
        'hero_subtitle', 'hero_subtitle_en', 'primary_cta', 'primary_cta_en',
        'secondary_cta', 'secondary_cta_en', 'email', 'whatsapp', 'admin_password_hash',
        'services', 'services_en', 'works', 'stats', 'stats_en', 'estimator_enabled', 'estimator_pricing', 'budget_ranges',
        'newsletter_title', 'newsletter_title_en', 'newsletter_desc', 'newsletter_desc_en',
        'process_steps', 'process_steps_en'
    ];

    protected $casts = [
        'services' => 'array',
        'services_en' => 'array',
        'works' => 'array',
        'stats' => 'array',
        'stats_en' => 'array',
        'estimator_enabled' => 'boolean',
        'estimator_pricing' => 'array',
        'budget_ranges' => 'array',
        'process_steps' => 'array',
        'process_steps_en' => 'array'
    ];

    public function getTaglineAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->tagline_en ?: $value;
        }
        return $value;
    }

    public function getHeroTitleAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->hero_title_en ?: $value;
        }
        return $value;
    }

    public function getHeroSubtitleAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->hero_subtitle_en ?: $value;
        }
        return $value;
    }

    public function getPrimaryCtaAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->primary_cta_en ?: $value;
        }
        return $value;
    }

    public function getSecondaryCtaAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->secondary_cta_en ?: $value;
        }
        return $value;
    }

    public function getServicesAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->services_en)) {
            return is_string($this->services_en) ? json_decode($this->services_en, true) : $this->services_en;
        }
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function getStatsAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->stats_en)) {
            return is_string($this->stats_en) ? json_decode($this->stats_en, true) : $this->stats_en;
        }
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function getProcessStepsAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            $enVal = $this->process_steps_en;
            if (empty($enVal)) {
                return self::defaults()['process_steps_en'];
            }
            return is_string($enVal) ? json_decode($enVal, true) : $enVal;
        }
        if (empty($value)) {
            return self::defaults()['process_steps'];
        }
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function getNewsletterTitleAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->newsletter_title_en ?: $value;
        }
        return $value ?: 'Dapatkan Penawaran Khusus';
    }

    public function getNewsletterDescAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->newsletter_desc_en ?: $value;
        }
        return $value ?: 'Masukkan email Anda untuk mendapatkan info promo pembuatan website dan konsultasi digital gratis dari kami.';
    }

    public function getWorksAttribute()
    {
        return \App\Models\PortfolioWork::where('is_active', true)
            ->where('title', '!=', '')
            ->whereNotNull('title')
            ->whereRaw('LENGTH(title) <= 120')
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public static function current(): self
    {
        return static::first() ?? static::create(static::defaults());
    }

    public static function defaults(): array
    {
        return [
            'brand_name' => 'Gany Labs',
            'logo_initials' => 'GL',
            'tagline' => 'Strategy-led digital studio for ambitious brands',
            'tagline_en' => 'Strategy-led digital studio for ambitious brands',
            'hero_title' => 'Turning Code Into Reality.           *Concept* to *Deployment*',
            'hero_title_en' => 'Turning Code Into Reality.           *Concept* to *Deployment*',
            'hero_subtitle' => 'A good website is an investment that pays off. Leave the technical complexity to Gany Labs. From premium visuals to robust systems, we are ready to design a professional website that is functional and brings real results for you.',
            'hero_subtitle_en' => 'A good website is an investment that pays off. Leave the technical complexity to Gany Labs. From premium visuals to robust systems, we are ready to design a professional website that is functional and brings real results for you.',
            'primary_cta' => 'Build a Website Now',
            'primary_cta_en' => 'Build a Website Now',
            'secondary_cta' => 'View Showcase',
            'secondary_cta_en' => 'View Showcase',
            'email' => 'ghaniyys@gmail.com',
            'whatsapp' => '6289670949392',
            'estimator_enabled' => false,
            'newsletter_title' => 'Dapatkan Penawaran Khusus',
            'newsletter_title_en' => 'Get Special Offers',
            'newsletter_desc' => 'Masukkan email Anda untuk mendapatkan info promo pembuatan website dan konsultasi digital gratis dari kami.',
            'newsletter_desc_en' => 'Enter your email to get info on website promos and free digital consultations from us.',
            'budget_ranges' => [
                'Di bawah Rp 1 juta',
                'Rp 1 - 3 juta',
                'Rp 3 - 5 juta',
                'Rp 5 juta+'
            ],
            'estimator_pricing' => [
                'base_prices' => [
                    'landing' => 3000000,
                    'compro' => 6000000,
                    'custom' => 12000000
                ],
                'feature_prices' => [
                    'animation' => 1500000,
                    'admin' => 2500000,
                    'seo' => 1000000,
                    'multilang' => 2000000
                ],
                'feature_labels' => [
                    'animation' => 'Premium Animations (+Rp 1.5jt)',
                    'admin' => 'Custom CMS / Admin (+Rp 2.5jt)',
                    'seo' => 'SEO Pack (+Rp 1jt)',
                    'multilang' => 'Multi-Language (+Rp 2jt)'
                ]
            ],
            'stats' => [
                ['value' => '42+', 'label' => 'projects shipped'],
                ['value' => '3.8x', 'label' => 'avg. inquiry elevator'],
                ['value' => '14d', 'label' => 'sprint prototype']
            ],
            'stats_en' => [
                ['value' => '42+', 'label' => 'projects shipped'],
                ['value' => '3.8x', 'label' => 'avg. inquiry elevator'],
                ['value' => '14d', 'label' => 'sprint prototype']
            ],
            'services' => [
                ['icon' => '✦', 'title' => 'Immersive Interface Design', 'body' => 'Presents an exclusive and interactive visual experience. The interface is designed from scratch without templates to ensure your digital identity appears unique, modern and leaves a lasting impression on visitors.'],
                ['icon' => '◈', 'title' => 'Custom Web Development', 'body' => 'Building a digital ecosystem with a robust system architecture. Specifically designed for high scalability, providing data security, and ease of managing your business operations.'],
                ['icon' => '↗', 'title' => 'Optimization & High Performance', 'body' => 'Aesthetics balanced with efficiency. We ensure every page is optimized for maximum loading speed, responsive across all devices, and search engine (SEO) friendly.']
            ],
            'services_en' => [
                ['icon' => '✦', 'title' => 'Immersive Interface Design', 'body' => 'Presents an exclusive and interactive visual experience. The interface is designed from scratch without templates to ensure your digital identity appears unique, modern and leaves a lasting impression on visitors.'],
                ['icon' => '◈', 'title' => 'Custom Web Development', 'body' => 'Building a digital ecosystem with a robust system architecture. Specifically designed for high scalability, providing data security, and ease of managing your business operations.'],
                ['icon' => '↗', 'title' => 'Optimization & High Performance', 'body' => 'Aesthetics balanced with efficiency. We ensure every page is optimized for maximum loading speed, responsive across all devices, and search engine (SEO) friendly.']
            ],
            'works' => [
                ['tag' => 'Corporate Websites', 'title' => 'Nebula Capital', 'body' => 'Investor-grade web profile dengan data storytelling, credibility blocks, dan high-trust contact journey.'],
                ['tag' => 'Product Launch', 'title' => 'OrbitOS', 'body' => 'Launch page SaaS dengan dashboard preview dan pricing-ready layout.'],
                ['tag' => 'Brand Refresh', 'title' => 'Velora Studio', 'body' => 'Portfolio architecture untuk creative studio biar case study lebih menjual.']
            ],
            'process_steps' => [
                [
                    'icon' => 'Discover',
                    'title' => 'Discover',
                    'metric' => 'Tactics, Research, & Plan',
                    'body' => 'Mapping goals, audience, and essential content to ensure a structured digital strategy that delivers real business impact.'
                ],
                [
                    'icon' => 'Design',
                    'title' => 'Design',
                    'metric' => 'UI/UX & High-End Aesthetics',
                    'body' => 'Premium, asymmetric, grid-breaking, and mobile-first visuals. We design a unique modern visual identity that captivates visitors at first sight.'
                ],
                [
                    'icon' => 'Build',
                    'title' => 'Build',
                    'metric' => 'Clean Code & 3D Hologram',
                    'body' => 'Laravel, MySQL, React, Three.js. We modernize codebases into modular, instant-speed, responsive systems easily managed via a custom CMS Admin Panel.'
                ],
                [
                    'icon' => 'Launch',
                    'title' => 'Launch',
                    'metric' => 'Zero-Downtime VPS Deploy',
                    'body' => 'Rigorous functionality testing, instant SEO optimization, domain configuration, high-performance VPS deployment, and administrative hand-off.'
                ]
            ],
            'process_steps_en' => [
                [
                    'icon' => 'Discover',
                    'title' => 'Discover',
                    'metric' => 'Tactics, Research, & Plan',
                    'body' => 'Mapping goals, audience, and essential content to ensure a structured digital strategy that delivers real business impact.'
                ],
                [
                    'icon' => 'Design',
                    'title' => 'Design',
                    'metric' => 'UI/UX & High-End Aesthetics',
                    'body' => 'Premium, asymmetric, grid-breaking, and mobile-first visuals. We design a unique modern visual identity that captivates visitors at first sight.'
                ],
                [
                    'icon' => 'Build',
                    'title' => 'Build',
                    'metric' => 'Clean Code & 3D Hologram',
                    'body' => 'Laravel, MySQL, React, Three.js. We modernize codebases into modular, instant-speed, responsive systems easily managed via a custom CMS Admin Panel.'
                ],
                [
                    'icon' => 'Launch',
                    'title' => 'Launch',
                    'metric' => 'Zero-Downtime VPS Deploy',
                    'body' => 'Rigorous functionality testing, instant SEO optimization, domain configuration, high-performance VPS deployment, and administrative hand-off.'
                ]
            ]
        ];
    }}
