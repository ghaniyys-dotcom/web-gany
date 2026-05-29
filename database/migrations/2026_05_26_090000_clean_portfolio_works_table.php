<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PortfolioWork;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Wipe all records strictly inside the portfolio_works table to clean up duplicated/fragmented entries
        PortfolioWork::query()->truncate();

        // 2. Reseed exactly the 3 original clean premium works
        PortfolioWork::create([
            'tag' => 'Corporate Websites',
            'tag_en' => 'Corporate Websites',
            'title' => 'Nebula Capital',
            'body' => 'Investor-grade web profile dengan data storytelling, credibility blocks, dan high-trust contact journey.',
            'body_en' => 'Investor-grade web profile with data storytelling, credibility blocks, and a high-trust contact journey.',
            'client' => 'Nebula Capital',
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
            'tag_en' => 'Product Launch',
            'title' => 'OrbitOS',
            'body' => 'Launch page SaaS dengan dashboard preview dan pricing-ready layout.',
            'body_en' => 'SaaS launch page featuring a dynamic dashboard preview and pricing-ready layouts.',
            'client' => 'OrbitOS',
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
            'tag_en' => 'Brand Refresh',
            'title' => 'Velora Studio',
            'body' => 'Portfolio architecture untuk creative studio biar case study lebih menjual.',
            'body_en' => 'Architectural portfolio showcase for a creative studio to elevate case studies.',
            'client' => 'Velora Studio',
            'image_url' => 'images/mockup_velora.png',
            'project_url' => 'https://velorastudio.demo',
            'challenge' => "Sebagai studio kreatif papan atas, portofolio lama Velora dirasa kurang mengekspresikan karakter estetik kelas dunia mereka.\n\nMereka membutuhkan website showcase yang bertindak sebagai galeri seni digital interaktif yang mewah dan minimalis.",
            'solution' => "Kami merancang tata letak asimetris dinamis yang didukung oleh sistem scrolling inertia Lenis untuk menciptakan efek scroll yang sangat mulus dan mewah.\n\nTransisi antar halaman dirancang menggunakan overlay tirai asimetris untuk meminimalkan jeda navigasi visual.",
            'results' => "Situs web dinobatkan sebagai 'Site of the Day' di platform kurator global CSS Design Awards dan berhasil mendatangkan 3 klien korporat kelas atas.",
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        PortfolioWork::query()->truncate();
    }
};
