<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\IntroSetting;
use App\Models\Newsletter;
use App\Models\Skill;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\FounderProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    protected function logVisit(Request $request, $pageUrl)
    {
        try {
            $ip = $request->ip();
            $ipHash = hash_hmac('sha256', $ip, (string) config('services.analytics.salt'));
            
            // Deduplicate: check if this IP hash has visited this page URL in the last 5 minutes (300 seconds)
            $cacheKey = 'visit_log:' . md5($ipHash . '_' . $pageUrl);
            if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                return;
            }
            
            // Set cache flag before writing to prevent race conditions
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, 300);
            
            \App\Models\AnalyticsEvent::create([
                'ip_hash' => $ipHash,
                'page_url' => $pageUrl,
                'event_type' => 'visit',
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            ]);
        } catch (\Throwable $e) {
            Log::error('Analytics log failed: ' . $e->getMessage());
        }
    }

    public function trackEvent(Request $request)
    {
        $request->validate([
            'event_type' => 'required|string|in:cal_click,budget_calc,live_site_click',
            'page_url' => 'required|string|max:255',
        ]);

        try {
            $ip = $request->ip();
            $ipHash = hash_hmac('sha256', $ip, (string) config('services.analytics.salt'));

            \App\Models\AnalyticsEvent::create([
                'ip_hash' => $ipHash,
                'page_url' => $request->input('page_url'),
                'event_type' => $request->input('event_type'),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $this->logVisit($request, '/');
        Skill::checkAndSeedDefaults();
        return view('welcome', [
            'site'         => SiteSetting::current(),
            'intro'        => IntroSetting::current(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(6)->get(),
            'faqs'         => Faq::where('is_active', true)->orderBy('sort_order')->take(8)->get(),
            'skills'       => Skill::where('is_active', true)->orderBy('sort_order')->get(),
            'orbitSkills'  => Skill::where('is_active', true)->where('in_orbit', true)->orderBy('sort_order')->get(),
            'founder'      => FounderProfile::current(),
        ]);
    }


    public function about(Request $request)
    {
        $this->logVisit($request, '/about');
        Skill::checkAndSeedDefaults();
        return view('about', [
            'site'   => SiteSetting::current(),
            'intro'  => IntroSetting::current(),
            'skills' => Skill::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function services(Request $request)
    {
        $this->logVisit($request, '/services');
        return view('services', ['site' => SiteSetting::current()]);
    }

    public function portfolio(Request $request)
    {
        $this->logVisit($request, '/portfolio');
        return view('portfolio', ['site' => SiteSetting::current()]);
    }

    public function workDetail(Request $request, $slug)
    {
        $this->logVisit($request, '/portfolio/' . $slug);
        $site = SiteSetting::current();
        $work = collect($site->works ?? [])->first(function ($w) use ($slug) {
            return \Illuminate\Support\Str::slug($w['title'] ?? '') === $slug;
        });

        if (!$work) {
            abort(404);
        }

        return view('portfolio-detail', compact('site', 'work'));
    }


    public function contact(Request $request)
    {
        if ($request->filled('website')) {
            Log::warning('Bot detected via honeypot', ['ip' => $request->ip()]);

            return back()->with('success', 'Pesan lu udah masuk.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160',
            'company' => 'nullable|string|max:160',
            'budget' => 'nullable|string|max:80',
            'message' => 'required|string|max:3000',
        ]);
        $data['subject'] = 'Website inquiry from '.$data['name'];
        $message = ContactMessage::create($data);

        Log::info('Contact form submitted', ['name' => $data['name'], 'email' => $data['email'], 'ip' => $request->ip()]);

        $notifyTo = config('site.notify_email') ?: SiteSetting::current()->email;
        if ($notifyTo) {
            try {
                Mail::to($notifyTo)->send(new ContactMessageReceived($message));
            } catch (\Throwable $e) {
                Log::error('Contact email failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Pesan lu udah masuk. Nanti kita cek dan balas secepatnya.');
    }

    public function newsletter(Request $request)
    {
        $request->validate(['newsletter_email' => 'required|email|max:160']);
        $exists = Newsletter::where('email', $request->newsletter_email)->first();
        if ($exists) {
            return back()->with('success', 'Email udah terdaftar.');
        }
        Newsletter::create(['email' => $request->newsletter_email]);
        Log::info('Newsletter subscribed', ['email' => $request->newsletter_email, 'ip' => $request->ip()]);

        return back()->with('success', 'Berhasil subscribe newsletter!');
    }
}
