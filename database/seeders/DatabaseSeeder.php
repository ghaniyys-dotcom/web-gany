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
        // 1. Site Settings
        $site = SiteSetting::query()->first();
        if (! $site) {
            $site = SiteSetting::create(SiteSetting::defaults());
        }

        if (empty($site->admin_password_hash) && ! env('ADMIN_PASSWORD_HASH')) {
            $site->update([
                'admin_password_hash' => Hash::make(env('ADMIN_PASSWORD', 'ganyadmin2026')),
                'logo_initials' => $site->logo_initials ?: 'AL',
            ]);
        }

        // 2. Intro Settings
        if (IntroSetting::count() === 0) {
            IntroSetting::create(IntroSetting::defaults());
        }

        // 3. Founder Profile
        if (FounderProfile::count() === 0) {
            FounderProfile::create(FounderProfile::defaults());
        }

        // 4. Testimonials
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

        // 5. FAQs
        if (Faq::count() === 0) {
            Faq::insert([
                [
                    'question' => 'Berapa lama project company profile selesai?',
                    'answer' => 'Biasanya 2-4 minggu tergantung jumlah halaman, konten, dan revisi.',
                    'sort_order' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah saya bisa edit konten sendiri?',
                    'answer' => 'Ya, ada admin panel untuk edit teks, services, portfolio, dan melihat pesan contact.',
                    'sort_order' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah websitenya mobile-friendly?',
                    'answer' => 'Semua layout dibuat mobile-first dan sudah diuji di berbagai ukuran layar.',
                    'sort_order' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 6. Skills & WebGL Orbit
        if (Skill::count() === 0) {
            $defaultSkills = Skill::defaults();
            foreach ($defaultSkills as $s) {
                // Preset orbit for selected key technologies
                $inOrbit = in_array($s['name'], ['Laravel', 'PHP', 'JavaScript', 'MySQL', 'CSS / Tailwind', 'UI / UX']);
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

        // 7. Portfolio Works (New Eloquent DB schema)
        if (PortfolioWork::count() === 0) {
            PortfolioWork::create([
                'tag' => 'Corporate Website',
                'title' => 'Nebula Capital',
                'body' => 'Investor-grade web profile dengan data storytelling, credibility blocks, dan high-trust contact journey.',
                'client' => 'Nebula Capital',
                'image_url' => null,
                'project_url' => 'https://nebula.asterialabs.id',
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
                'client' => 'Orbit Technologies Group',
                'image_url' => null,
                'project_url' => 'https://orbitos.asterialabs.id',
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
                'client' => 'Velora Creative Partners',
                'image_url' => null,
                'project_url' => 'https://velora.asterialabs.id',
                'challenge' => "Sebagai studio kreatif papan atas, portofolio lama Velora dirasa kurang mengekspresikan karakter estetik kelas dunia mereka.\n\nMereka membutuhkan website showcase yang bertindak sebagai galeri seni digital interaktif yang mewah dan minimalis.",
                'solution' => "Kami merancang tata letak asimetris dinamis yang didukung oleh sistem scrolling inertia Lenis untuk menciptakan efek scroll yang sangat mulus dan mewah.\n\nTransisi antar halaman dirancang menggunakan overlay tirai asimetris untuk meminimalkan jeda navigasi visual.",
                'results' => "Situs web dinobatkan sebagai 'Site of the Day' di platform kurator global CSS Design Awards dan berhasil mendatangkan 3 klien korporat kelas atas.",
                'sort_order' => 2,
                'is_active' => true,
            ]);
        }
    }
}
