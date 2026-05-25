<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\IntroSetting;
use App\Models\FounderProfile;
use App\Models\Skill;
use App\Models\PortfolioWork;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Site Settings — Gany Labs data aktual
        $site = SiteSetting::query()->first();
        if (! $site) {
            $site = SiteSetting::create(SiteSetting::defaults());
        }

        if (empty($site->admin_password_hash) && ! env('ADMIN_PASSWORD_HASH')) {
            $site->update([
                'admin_password_hash' => Hash::make(env('ADMIN_PASSWORD', 'ganyadmin2026')),
                'logo_initials' => $site->logo_initials ?: 'GL',
            ]);
        }

        // 2. Intro Settings — data aktual dari localhost
        if (IntroSetting::count() === 0) {
            IntroSetting::create([
                'is_enabled'           => true,
                'greeting'             => 'welcome',
                'greeting_en'          => 'welcome',
                'name'                 => "Gany's Portofolio",
                'roles'                => ['Full-Stack Developer', 'UI/UX Enthusiast', 'Laravel Engineer', 'Software Engineer'],
                'tagline'              => 'Build premium and functional digital products.',
                'tagline_en'           => 'Build premium and functional digital products.',
                'cta_text'             => 'See My Projects',
                'cta_text_en'          => 'See My Projects',
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
            ]);
        }

        // 3. Founder Profile — data aktual
        if (FounderProfile::count() === 0) {
            FounderProfile::create([
                'eyebrow'        => 'MEET THE FOUNDER',
                'eyebrow_en'     => 'MEET THE FOUNDER',
                'heading'        => "Hi, I'm Gany.",
                'heading_en'     => "Hi, I'm Gany.",
                'description'    => "Gua adalah software engineer yang berdedikasi penuh untuk merancang dan membangun produk digital kelas premium. Di Gany Labs, gua percaya bahwa website bukan sekadar kode dan interface fungsional biasa. Setiap detail visual harus dirawat dengan taste seni tinggi agar memancarkan kesan eksklusif dan mahal. Melalui strategi, desain estetik, serta arsitektur kode modern, gua siap membantu lo menaikkan nilai brand di mata publik dan memikat klien-klien terbaik.",
                'description_en' => "I am a dedicated software engineer specializing in designing and building premium digital products. At Gany Labs, I believe a website is not just code and a functional interface. Every visual detail must be crafted with high artistic taste to radiate an exclusive and premium feel. Through strategy, aesthetic design, and modern code architecture, I am ready to help you elevate your brand value and attract the best clients.",
                'photo_path'     => null,
                'signature_path' => null,
            ]);
        }

        // 4. Testimonials — data aktual dari localhost
        if (Testimonial::count() === 0) {
            Testimonial::create([
                'name' => 'Rina Aditia',
                'role' => 'Founder',
                'company' => 'Nebula Capital',
                'quote' => 'Looks premium, loads fast, and finally makes the company feel legit online.',
                'rating' => 5,
                'is_active' => true,
            ]);
            Testimonial::create([
                'name' => 'Dimas Pratama',
                'role' => 'Marketing Lead',
                'company' => 'OrbitOS',
                'quote' => 'Inquiry naik signifikan setelah company profile baru live. Admin panel-nya juga gampang.',
                'rating' => 5,
                'is_active' => true,
            ]);
        }

        // 5. FAQs — data aktual dari localhost
        if (Faq::count() === 0) {
            Faq::insert([
                [
                    'question' => 'How long does it take for the company profile project to be completed?',
                    'answer' => 'Usually 2-4 weeks depending on number of pages, content, and revisions.',
                    'sort_order' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Can I edit content myself?',
                    'answer' => 'Yes, there is an admin panel for editing text, services, portfolio, and viewing contact messages.',
                    'sort_order' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Is the website mobile-friendly?',
                    'answer' => 'All layouts are made mobile-first and have been tested on various screen sizes.',
                    'sort_order' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 6. Skills — data aktual dari localhost (15 skills)
        if (Skill::count() === 0) {
            $skills = [
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
                ['name' => 'React',         'level' => 90, 'years' => 3, 'category' => 'Frontend', 'color' => '#61dafb', 'sort_order' => 13],
                ['name' => 'Three.js',      'level' => 80, 'years' => 2, 'category' => 'Frontend', 'color' => '#ffffff', 'sort_order' => 14],
                ['name' => 'Figma',         'level' => 85, 'years' => 3, 'category' => 'Design',   'color' => '#f24e1e', 'sort_order' => 15],
            ];
            foreach ($skills as $s) {
                $inOrbit = in_array($s['name'], ['Laravel', 'PHP', 'JavaScript', 'MySQL', 'CSS / Tailwind', 'React', 'Three.js', 'Figma']);
                Skill::create([
                    'name' => $s['name'],
                    'level' => $s['level'],
                    'years' => $s['years'],
                    'category' => $s['category'],
                    'color' => $s['color'],
                    'sort_order' => $s['sort_order'],
                    'is_active' => true,
                    'in_orbit' => $inOrbit,
                ]);
            }
        }

        // 7. Portfolio Works — data aktual dari localhost
        if (PortfolioWork::count() === 0) {
            PortfolioWork::create([
                'tag' => 'Corporate Websites',
                'title' => 'Nebula Capital',
                'body' => 'Investor-grade web profile dengan data storytelling, credibility blocks, dan high-trust contact journey.',
                'client' => '',
                'image_url' => 'images/mockup_nebula.png',
                'project_url' => 'https://nebulacapital.demo',
                'challenge' => "Tantangan utama proyek ini adalah mendesain landing page institusional yang mampu mengomunikasikan rasa percaya tingkat tinggi bagi calon investor besar.\n\nHalaman profile harus terlihat mahal, memuat data storytelling yang meyakinkan, serta memuat grafik kinerja visual yang responsif tanpa menurunkan kecepatan waktu respons server.",
                'solution' => "Solusi kami adalah merancang antarmuka berbasis dark-mode premium yang asimetris, menerapkan skema warna monokromatik abu-abu gelap dengan sentuhan fiery-orange, serta membagi porsi rendering visual.\n\nKecepatan memuat halaman dioptimalkan dengan mengeliminasi skrip pihak ketiga yang menghalangi rendering paint awal.",
                'results' => "Menghasilkan peningkatan inbound inquiries sebesar 3.8x rata-rata per bulan dan meningkatkan rata-rata retensi pengunjung halaman hingga 180 detik.",
                'sort_order' => 0,
                'is_active' => true,
            ]);

            PortfolioWork::create([
                'tag' => 'Product Launch',
                'title' => 'OrbitOS',
                'body' => 'Launch page SaaS dengan dashboard preview dan pricing-ready layout.',
                'client' => '',
                'image_url' => 'images/mockup_orbit.png',
                'project_url' => 'https://orbitos.demo',
                'challenge' => "Mempromosikan sistem operasi cloud SaaS baru memerlukan pendekatan visual revolusioner untuk memukau calon pengguna.\n\nTantangan kami adalah merepresentasikan interaksi dashboard yang kompleks ke dalam landing page statis tanpa membuat pengunjung bosan.",
                'solution' => "Kami mengintegrasikan model hologram 3D interaktif berbasis WebGL (Three.js dan React Three Fiber) yang bereaksi terhadap gerakan kursor pengguna.\n\nIni dipadukan dengan kalkulator biaya estimasi real-time di bagian bawah halaman untuk menjembatani ketertarikan langsung ke tindakan konversi.",
                'results' => "Target konversi pendaftaran pengguna versi beta terlampaui sebesar 45% dalam dua minggu pertama peluncuran kampanye.",
                'sort_order' => 1,
                'is_active' => true,
            ]);

            PortfolioWork::create([
                'tag' => 'Brand Refresh',
                'title' => 'Velora Studio',
                'body' => 'Portfolio architecture untuk creative studio biar case study lebih menjual.',
                'client' => '',
                'image_url' => 'images/mockup_velora.png',
                'project_url' => 'https://velorastudio.demo',
                'challenge' => "Sebagai studio kreatif papan atas, portofolio lama Velora dirasa kurang mengekspresikan karakter estetik kelas dunia mereka.\n\nMereka membutuhkan website showcase yang bertindak sebagai galeri seni digital interaktif yang mewah dan minimalis.",
                'solution' => "Kami merancang tata letak asimetris dinamis yang didukung oleh sistem scrolling inertia Lenis untuk menciptakan efek scroll yang sangat mulus dan mewah.\n\nTransisi antar halaman dirancang menggunakan overlay tirai asimetris untuk meminimalkan jeda navigasi visual.",
                'results' => "Situs web dinobatkan sebagai 'Site of the Day' di platform kurator global CSS Design Awards dan berhasil mendatangkan 3 klien korporat kelas atas.",
                'sort_order' => 2,
                'is_active' => true,
            ]);
        }
    }
}
