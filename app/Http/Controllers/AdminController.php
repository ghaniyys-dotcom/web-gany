<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\IntroSetting;
use App\Models\Newsletter;
use App\Models\Skill;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\FounderProfile;
use App\Support\AdminPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AnalyticsEvent;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => 'required']);

        if (! AdminPassword::verify($request->password)) {
            Log::warning('Admin login failed', ['ip' => $request->ip()]);

            return back()->with('error', 'Password salah.');
        }

        $request->session()->regenerate();
        session(['admin_logged_in' => true]);
        Log::info('Admin logged in', ['ip' => $request->ip()]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logout berhasil.');
    }

    public function dashboard()
    {
        // Fetch privacy-friendly aggregated stats
        $totalVisits = AnalyticsEvent::where('event_type', 'visit')->count();
        $uniqueVisitors = AnalyticsEvent::distinct('ip_hash')->count('ip_hash');
        $calClicks = AnalyticsEvent::where('event_type', 'cal_click')->count();
        $budgetCalcs = AnalyticsEvent::where('event_type', 'budget_calc')->count();

        // Fetch last 15 days of trend data for our custom glowing line chart
        $chartData = [];
        for ($i = 14; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            $visits = AnalyticsEvent::where('event_type', 'visit')
                ->whereDate('created_at', $date)
                ->count();
                
            $uniques = AnalyticsEvent::where('event_type', 'visit')
                ->whereDate('created_at', $date)
                ->distinct('ip_hash')
                ->count('ip_hash');

            $chartData[] = [
                'date' => Carbon::now()->subDays($i)->format('d M'),
                'visits' => $visits,
                'uniques' => $uniques,
            ];
        }

        return view('admin.dashboard', [
            'site' => SiteSetting::current(),
            'messages' => ContactMessage::latest()->take(6)->get(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'total' => ContactMessage::count(),
            'visits_count' => $totalVisits,
            'uniques_count' => $uniqueVisitors,
            'cal_clicks_count' => $calClicks,
            'budget_calcs_count' => $budgetCalcs,
            'chart_data' => $chartData,
        ]);
    }

    public function editContent()
    {
        return view('admin.content', ['site' => SiteSetting::current()]);
    }

    public function updateContent(Request $request)
    {
        $site = SiteSetting::current();
        $data = $request->validate([
            'brand_name' => 'required|string|max:120',
            'logo_initials' => 'nullable|string|max:8',
            'tagline' => 'nullable|string|max:200',
            'tagline_en' => 'nullable|string|max:200',
            'hero_title' => 'required|string|max:300',
            'hero_title_en' => 'nullable|string|max:300',
            'hero_subtitle' => 'required|string|max:600',
            'hero_subtitle_en' => 'nullable|string|max:600',
            'primary_cta' => 'required|string|max:80',
            'primary_cta_en' => 'nullable|string|max:80',
            'secondary_cta' => 'required|string|max:80',
            'secondary_cta_en' => 'nullable|string|max:80',
            'email' => 'required|email|max:160',
            'whatsapp' => 'nullable|string|max:40',
            'newsletter_title' => 'nullable|string|max:200',
            'newsletter_title_en' => 'nullable|string|max:200',
            'newsletter_desc' => 'nullable|string',
            'newsletter_desc_en' => 'nullable|string',
        ]);
        $data['stats'] = $this->parseLines($request->input('stats_lines'), 2, ['value', 'label']);
        $data['stats_en'] = $this->parseLines($request->input('stats_lines_en'), 2, ['value', 'label']);
        $data['services'] = $this->parseLines($request->input('services_lines'), 3, ['icon', 'title', 'body']);
        $data['services_en'] = $this->parseLines($request->input('services_lines_en'), 3, ['icon', 'title', 'body']);
        
        // Parse works
        $worksData = $this->parseLines($request->input('works_lines'), 10, ['tag', 'title', 'body', 'image_url', 'project_url', 'client', 'challenge', 'solution', 'tech_stack', 'results']);
        $worksDataEn = $this->parseLines($request->input('works_lines_en'), 10, ['tag', 'title', 'body', 'image_url', 'project_url', 'client', 'challenge', 'solution', 'tech_stack', 'results']);
        
        // Sync works to the proper database table 'portfolio_works' via model 'PortfolioWork'
        \App\Models\PortfolioWork::query()->delete();
        foreach ($worksData as $index => $w) {
            $wEn = $worksDataEn[$index] ?? [];
            \App\Models\PortfolioWork::create([
                'tag' => $w['tag'] ?? '',
                'tag_en' => $wEn['tag'] ?? null,
                'title' => $w['title'] ?? '',
                'body' => $w['body'] ?? '',
                'body_en' => $wEn['body'] ?? null,
                'image_url' => $w['image_url'] ?? null,
                'project_url' => $w['project_url'] ?? null,
                'client' => $w['client'] ?? null,
                'challenge' => $w['challenge'] ?? null,
                'challenge_en' => $wEn['challenge'] ?? null,
                'solution' => $w['solution'] ?? null,
                'solution_en' => $wEn['solution'] ?? null,
                'tech_stack' => $w['tech_stack'] ?? null,
                'results' => $w['results'] ?? null,
                'results_en' => $wEn['results'] ?? null,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        // Exclude obsolete works key and update SiteSetting
        unset($data['works']);
        
        // Add dynamic estimator settings
        $data['estimator_enabled'] = $request->boolean('estimator_enabled');
        
        $basePrices = [
            'landing' => (int) $request->input('price_landing', 3000000),
            'compro' => (int) $request->input('price_compro', 6000000),
            'custom' => (int) $request->input('price_custom', 12000000),
        ];

        $featurePrices = [
            'animation' => (int) $request->input('price_animation', 1500000),
            'admin' => (int) $request->input('price_admin', 2500000),
            'seo' => (int) $request->input('price_seo', 1000000),
            'multilang' => (int) $request->input('price_multilang', 2000000),
        ];

        $formatPriceDynamic = function ($val) {
            if ($val >= 1000000) {
                $jt = $val / 1000000;
                return ($jt == (int)$jt) ? (int)$jt . 'jt' : number_format($jt, 1, ',', '') . 'jt';
            } else {
                $rb = $val / 1000;
                return ($rb == (int)$rb) ? (int)$rb . 'rb' : number_format($rb, 1, ',', '') . 'rb';
            }
        };

        $featureLabels = [
            'animation' => 'Premium Animations (+Rp ' . $formatPriceDynamic($featurePrices['animation']) . ')',
            'admin' => 'Custom CMS / Admin (+Rp ' . $formatPriceDynamic($featurePrices['admin']) . ')',
            'seo' => 'SEO Pack (+Rp ' . $formatPriceDynamic($featurePrices['seo']) . ')',
            'multilang' => 'Multi-Language (+Rp ' . $formatPriceDynamic($featurePrices['multilang']) . ')',
        ];

        $data['estimator_pricing'] = [
            'base_prices' => $basePrices,
            'feature_prices' => $featurePrices,
            'feature_labels' => $featureLabels,
        ];

        // Parse and store budget ranges from lines
        $rangesText = $request->input('budget_ranges_lines');
        $data['budget_ranges'] = array_values(array_filter(array_map('trim', explode("\n", (string)$rangesText))));

        $site->update($data);
        Log::info('Site content updated', ['ip' => $request->ip()]);

        return back()->with('success', 'Konten website berhasil diupdate.');
    }

    public function messages()
    {
        return view('admin.messages', ['messages' => ContactMessage::latest()->paginate(12)]);
    }

    public function showMessage(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return view('admin.message-detail', ['message' => $message]);
    }

    public function destroyMessage(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages')->with('success', 'Message dihapus.');
    }

    public function testimonials()
    {
        return view('admin.testimonials', ['items' => Testimonial::latest()->get()]);
    }

    public function storeTestimonial(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:120',
            'company' => 'nullable|string|max:120',
            'quote' => 'required|string|max:1000',
            'quote_en' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['rating'] = $data['rating'] ?? 5;
        Testimonial::create($data);

        return back()->with('success', 'Testimonial ditambahkan.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:120',
            'company' => 'nullable|string|max:120',
            'quote' => 'required|string|max:1000',
            'quote_en' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['rating'] = $data['rating'] ?? 5;
        $testimonial->update($data);

        return back()->with('success', 'Testimonial diupdate.');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testimonial dihapus.');
    }

    public function faqs()
    {
        return view('admin.faqs', ['items' => Faq::orderBy('sort_order')->get()]);
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:300',
            'question_en' => 'nullable|string|max:300',
            'answer' => 'required|string|max:2000',
            'answer_en' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        Faq::create($data);

        return back()->with('success', 'FAQ ditambahkan.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required|string|max:300',
            'question_en' => 'nullable|string|max:300',
            'answer' => 'required|string|max:2000',
            'answer_en' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $faq->update($data);

        return back()->with('success', 'FAQ diupdate.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ dihapus.');
    }

    public function newsletters()
    {
        return view('admin.newsletters', ['items' => Newsletter::latest()->paginate(20)]);
    }

    public function showPasswordForm()
    {
        return view('admin.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! AdminPassword::verify($request->current_password)) {
            return back()->with('error', 'Password lama salah.');
        }

        AdminPassword::update($request->password);

        return back()->with('success', 'Password admin berhasil diubah.');
    }

    public function editIntro()
    {
        return view('admin.intro', ['intro' => IntroSetting::current()]);
    }

    public function updateIntro(Request $request)
    {
        $intro = IntroSetting::current();
        $intro->update([
            'is_enabled'           => $request->boolean('is_enabled'),
            'greeting'             => $request->input('greeting', 'Halo 👋'),
            'greeting_en'          => $request->input('greeting_en'),
            'name'                 => $request->input('name', ''),
            'roles'                => array_filter(array_map('trim', explode("\n", $request->input('roles_text', '')))),
            'tagline'              => $request->input('tagline', ''),
            'tagline_en'           => $request->input('tagline_en'),
            'cta_text'             => $request->input('cta_text', 'Lihat Karya Gua →'),
            'cta_text_en'          => $request->input('cta_text_en'),
            'availability_enabled' => $request->boolean('availability_enabled'),
            'is_available'         => $request->boolean('is_available'),
            'availability_text'    => $request->input('availability_text', ''),
            'availability_text_en' => $request->input('availability_text_en'),
            'expertise_tickers'    => array_filter(array_map('trim', explode("\n", $request->input('tickers_text', '')))),
        ]);
        Log::info('Intro settings updated', ['ip' => $request->ip()]);

        return back()->with('success', 'Intro settings berhasil diupdate.');
    }

    public function skills()
    {
        return view('admin.skills', ['items' => Skill::orderBy('sort_order')->get()]);
    }

    public function storeSkill(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:80',
            'level'      => 'required|integer|min:0|max:100',
            'years'      => 'required|integer|min:0|max:50',
            'category'   => 'nullable|string|max:80',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['in_orbit']   = $request->boolean('in_orbit');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['color']      = $data['color'] ?: '#6246ea';
        Skill::create($data);

        return back()->with('success', 'Skill ditambahkan.');
    }

    public function updateSkill(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:80',
            'level'      => 'required|integer|min:0|max:100',
            'years'      => 'required|integer|min:0|max:50',
            'category'   => 'nullable|string|max:80',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['in_orbit']   = $request->boolean('in_orbit');
        $data['color']     = $data['color'] ?: '#6246ea';
        $skill->update($data);

        return back()->with('success', 'Skill diupdate.');
    }


    public function destroySkill(Skill $skill)
    {
        $skill->delete();

        return back()->with('success', 'Skill dihapus.');
    }

    public function editFounder()
    {
        return view('admin.founder', ['founder' => FounderProfile::current()]);
    }

    public function updateFounder(Request $request)
    {
        $request->validate([
            'eyebrow' => 'required|string|max:120',
            'eyebrow_en' => 'nullable|string|max:120',
            'heading' => 'required|string|max:200',
            'heading_en' => 'nullable|string|max:200',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:1024',
        ]);

        $founder = FounderProfile::current();
        
        $data = [
            'eyebrow' => $request->input('eyebrow'),
            'eyebrow_en' => $request->input('eyebrow_en'),
            'heading' => $request->input('heading'),
            'heading_en' => $request->input('heading_en'),
            'description' => $request->input('description'),
            'description_en' => $request->input('description_en'),
        ];

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->convertToWebp($request->file('photo'), 'founder');
        }

        if ($request->hasFile('signature')) {
            $data['signature_path'] = $this->convertToWebp($request->file('signature'), 'signature');
        }

        $founder->update($data);
        Log::info('Founder profile updated', ['ip' => $request->ip()]);

        return back()->with('success', 'Profil founder berhasil diupdate.');
    }

    private function convertToWebp($file, string $prefix): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $prefix . '_' . time() . '.webp';
        $destinationPath = public_path('uploads');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $targetPath = $destinationPath . '/' . $filename;

        if (function_exists('imagewebp') && in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $image = null;
            if ($extension === 'jpeg' || $extension === 'jpg') {
                $image = @imagecreatefromjpeg($tempPath);
            } elseif ($extension === 'png') {
                $image = @imagecreatefrompng($tempPath);
            } elseif ($extension === 'webp') {
                $image = @imagecreatefromwebp($tempPath);
            }

            if ($image) {
                if ($extension === 'png' || $extension === 'webp') {
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                }

                if (imagewebp($image, $targetPath, 85)) {
                    imagedestroy($image);
                    return 'uploads/' . $filename;
                }
                imagedestroy($image);
            }
        }

        // Fallback
        $fallbackFilename = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fallbackFilename);
        return 'uploads/' . $fallbackFilename;
    }

    private function parseLines(?string $text, int $parts, array $keys): array
    {
        $items = [];
        foreach (preg_split('/\r\n|\r|\n/', trim((string) $text)) as $line) {
            if (trim($line) === '') {
                continue;
            }
            $chunks = array_map('trim', explode('|', $line));
            $item = [];
            foreach ($keys as $i => $key) {
                $item[$key] = $chunks[$i] ?? '';
            }
            $items[] = $item;
        }

        return $items;
    }
}
