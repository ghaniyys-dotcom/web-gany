<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\IntroSetting;
use App\Models\Newsletter;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Support\AdminPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PortfolioFeatureTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    //  Helpers
    // ---------------------------------------------------------------

    private function seedSiteSetting(): SiteSetting
    {
        return SiteSetting::current();
    }

    private function seedIntro(): IntroSetting
    {
        return IntroSetting::current();
    }

    private function getAdminPassword(): string
    {
        return 'admin123';
    }

    private function setAdminPassword(): void
    {
        $site = $this->seedSiteSetting();
        $site->update(['admin_password_hash' => Hash::make($this->getAdminPassword())]);
    }

    private function loginAsAdmin(): void
    {
        $this->setAdminPassword();
        $this->post(route('admin.login.post'), ['password' => $this->getAdminPassword()]);
    }

    // ===============================================================
    //  PUBLIC PAGES — HTTP 200
    // ===============================================================

    public function test_home_page_returns_200(): void
    {
        $this->seedSiteSetting();
        Skill::checkAndSeedDefaults();

        $this->get(route('home'))->assertStatus(200);
    }

    public function test_about_page_returns_200(): void
    {
        $this->seedSiteSetting();
        Skill::checkAndSeedDefaults();

        $this->get(route('about'))->assertStatus(200);
    }

    public function test_services_page_returns_200(): void
    {
        $this->seedSiteSetting();
        $this->get(route('services'))->assertStatus(200);
    }

    public function test_portfolio_page_returns_200(): void
    {
        $this->seedSiteSetting();
        $this->get(route('portfolio'))->assertStatus(200);
    }

    // ===============================================================
    //  PORTFOLIO DETAIL
    // ===============================================================

    public function test_portfolio_detail_returns_404_for_invalid_slug(): void
    {
        $this->seedSiteSetting();
        $this->get(route('portfolio.detail', ['slug' => 'nonexistent-project']))
            ->assertStatus(404);
    }

    public function test_portfolio_detail_returns_200_for_valid_slug(): void
    {
        $site = $this->seedSiteSetting();
        // SiteSetting::defaults() include works; after seeding the DB the
        // accessor pulls from the works JSON column.  We can rely on the
        // default "Nebula Capital" entry whose slug is "nebula-capital".
        // But the works accessor now reads from PortfolioWork model table
        // (see SiteSetting::getWorksAttribute).  Seed a PortfolioWork so
        // workDetail() can find it.
        \App\Models\PortfolioWork::create([
            'tag'        => 'Corporate',
            'title'      => 'Nebula Capital',
            'body'       => 'Investor-grade web profile',
            'sort_order' => 0,
            'is_active'  => true,
        ]);

        $this->get(route('portfolio.detail', ['slug' => 'nebula-capital']))
            ->assertStatus(200);
    }

    // ===============================================================
    //  LANGUAGE SWITCHING
    // ===============================================================

    public function test_language_switch_to_english(): void
    {
        $response = $this->get(route('lang.switch', ['locale' => 'en']));
        $response->assertStatus(302);
        $this->assertEquals('en', session('locale'));
    }

    public function test_language_switch_to_indonesian(): void
    {
        $response = $this->get(route('lang.switch', ['locale' => 'id']));
        $response->assertStatus(302);
        $this->assertEquals('id', session('locale'));
    }

    public function test_language_switch_invalid_locale_does_not_change_session(): void
    {
        $response = $this->get(route('lang.switch', ['locale' => 'fr']));
        $response->assertStatus(302);
        $this->assertNotEquals('fr', session('locale'));
    }

    // ===============================================================
    //  CONTACT FORM
    // ===============================================================

    public function test_contact_requires_name(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'email'   => 'test@example.com',
            'message' => 'Hello there',
        ])
            ->assertSessionHasErrors('name');
    }

    public function test_contact_requires_email(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'name'    => 'John',
            'message' => 'Hello there',
        ])
            ->assertSessionHasErrors('email');
    }

    public function test_contact_requires_message(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'name'  => 'John',
            'email' => 'test@example.com',
        ])
            ->assertSessionHasErrors('message');
    }

    public function test_contact_rejects_invalid_email(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'name'    => 'John',
            'email'   => 'not-an-email',
            'message' => 'Hello',
        ])
            ->assertSessionHasErrors('email');
    }

    public function test_contact_honeypot_spam_protection(): void
    {
        $this->seedSiteSetting();

        $response = $this->post(route('contact.store'), [
            'name'    => 'Bot',
            'email'   => 'bot@spam.com',
            'message' => 'Buy crypto now!',
            'website' => 'spam-site.com',
        ]);

        // Honeypot filled → silently returns success but does NOT create a DB record
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', ['email' => 'bot@spam.com']);
    }

    public function test_contact_successful_submission_creates_db_record(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
            'company' => 'Acme Corp',
            'budget'  => 'Rp 5 - 10 juta',
            'message' => 'I need a website',
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
            'company' => 'Acme Corp',
            'budget'  => 'Rp 5 - 10 juta',
            'message' => 'I need a website',
        ]);
    }

    public function test_contact_optional_fields_can_be_empty(): void
    {
        $this->seedSiteSetting();

        $this->post(route('contact.store'), [
            'name'    => 'Jane',
            'email'   => 'jane@example.com',
            'message' => 'Just saying hi',
        ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', [
            'name'    => 'Jane',
            'company' => null,
            'budget'  => null,
        ]);
    }

    // ===============================================================
    //  NEWSLETTER
    // ===============================================================

    public function test_newsletter_requires_email(): void
    {
        $this->post(route('newsletter.store'), [])
            ->assertSessionHasErrors('newsletter_email');
    }

    public function test_newsletter_rejects_invalid_email(): void
    {
        $this->post(route('newsletter.store'), ['newsletter_email' => 'nope'])
            ->assertSessionHasErrors('newsletter_email');
    }

    public function test_newsletter_successful_subscription(): void
    {
        $this->post(route('newsletter.store'), ['newsletter_email' => 'sub@scriber.com'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('newsletters', ['email' => 'sub@scriber.com']);
    }

    public function test_newsletter_duplicate_email_returns_already_registered_message(): void
    {
        // First subscription
        Newsletter::create(['email' => 'dup@example.com']);

        $response = $this->post(route('newsletter.store'), ['newsletter_email' => 'dup@example.com']);
        $response->assertSessionHas('success');

        // Should still only have one record
        $this->assertEquals(1, Newsletter::where('email', 'dup@example.com')->count());
    }

    // ===============================================================
    //  ROBOTS.TXT
    // ===============================================================

    public function test_robots_txt_returns_plain_text(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function test_robots_txt_contains_expected_directives(): void
    {
        $response = $this->get('/robots.txt');
        $content = $response->getContent();

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Sitemap:', $content);
    }

    // ===============================================================
    //  SITEMAP.XML
    // ===============================================================

    public function test_sitemap_xml_returns_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $contentType = $response->headers->get('Content-Type');
        $this->assertStringStartsWith('application/xml', $contentType);
    }

    public function test_sitemap_xml_contains_valid_structure(): void
    {
        $response = $this->get('/sitemap.xml');
        $content = $response->getContent();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset', $content);
        $this->assertStringContainsString('</urlset>', $content);
    }

    public function test_sitemap_xml_contains_key_urls(): void
    {
        $response = $this->get('/sitemap.xml');
        $content = $response->getContent();

        $this->assertStringContainsString('<loc>' . url('/') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/about') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/services') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/portfolio') . '</loc>', $content);
    }

    // ===============================================================
    //  ADMIN ACCESS CONTROL
    // ===============================================================

    public function test_admin_login_page_accessible(): void
    {
        $this->get(route('admin.login'))->assertStatus(200);
    }

    public function test_admin_dashboard_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_content_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.content'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_messages_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.messages'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_testimonials_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.testimonials'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_faqs_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.faqs'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_skills_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.skills'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_newsletters_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.newsletters'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_password_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.password'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_intro_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.intro'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_founder_redirects_when_not_authenticated(): void
    {
        $this->get(route('admin.founder'))
            ->assertRedirect(route('admin.login'));
    }

    // ===============================================================
    //  ADMIN AUTHENTICATION
    // ===============================================================

    public function test_admin_login_with_wrong_password_shows_error(): void
    {
        $this->setAdminPassword();

        $this->post(route('admin.login.post'), ['password' => 'wrongpassword'])
            ->assertSessionHas('error');
    }

    public function test_admin_login_with_correct_password_redirects_to_dashboard(): void
    {
        $this->setAdminPassword();

        $this->post(route('admin.login.post'), ['password' => $this->getAdminPassword()])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_logout(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        // After logout, admin pages should redirect again
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    // ===============================================================
    //  ADMIN DASHBOARD
    // ===============================================================

    public function test_admin_dashboard_accessible_when_authenticated(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.dashboard'))->assertStatus(200);
    }

    // ===============================================================
    //  ADMIN TESTIMONIALS CRUD
    // ===============================================================

    public function test_admin_testimonials_index_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.testimonials'))->assertStatus(200);
    }

    public function test_admin_can_create_testimonial(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.testimonials.store'), [
            'name'     => 'Budi Santoso',
            'role'     => 'CEO',
            'company'  => 'PT Maju',
            'quote'    => 'Pelayanan sangat memuaskan!',
            'rating'   => 5,
            'is_active' => true,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'name'    => 'Budi Santoso',
            'role'    => 'CEO',
            'company' => 'PT Maju',
        ]);
    }

    public function test_admin_testimonial_requires_name(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.testimonials.store'), [
            'quote' => 'Missing name',
        ])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_testimonial_requires_quote(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.testimonials.store'), [
            'name' => 'No Quote Person',
        ])
            ->assertSessionHasErrors('quote');
    }

    public function test_admin_can_update_testimonial(): void
    {
        $this->loginAsAdmin();

        $testimonial = Testimonial::create([
            'name'     => 'Original',
            'role'     => 'Dev',
            'quote'    => 'Original quote',
            'rating'   => 4,
            'is_active' => true,
        ]);

        $this->put(route('admin.testimonials.update', $testimonial), [
            'name'     => 'Updated Name',
            'role'     => 'Senior Dev',
            'quote'    => 'Updated quote',
            'rating'   => 5,
            'is_active' => true,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'id'   => $testimonial->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $this->loginAsAdmin();

        $testimonial = Testimonial::create([
            'name'     => 'To Delete',
            'quote'    => 'Will be removed',
            'rating'   => 3,
            'is_active' => true,
        ]);

        $this->delete(route('admin.testimonials.destroy', $testimonial))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    // ===============================================================
    //  ADMIN FAQS CRUD
    // ===============================================================

    public function test_admin_faqs_index_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.faqs'))->assertStatus(200);
    }

    public function test_admin_can_create_faq(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.faqs.store'), [
            'question'   => 'Apa itu Gany Labs?',
            'answer'     => 'Gany Labs adalah studio digital.',
            'sort_order' => 1,
            'is_active'  => true,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('faqs', ['question' => 'Apa itu Gany Labs?']);
    }

    public function test_admin_faq_requires_question(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.faqs.store'), [
            'answer' => 'Missing question',
        ])
            ->assertSessionHasErrors('question');
    }

    public function test_admin_faq_requires_answer(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.faqs.store'), [
            'question' => 'Missing answer?',
        ])
            ->assertSessionHasErrors('answer');
    }

    public function test_admin_can_update_faq(): void
    {
        $this->loginAsAdmin();

        $faq = Faq::create([
            'question'   => 'Old question?',
            'answer'     => 'Old answer.',
            'sort_order' => 0,
            'is_active'  => true,
        ]);

        $this->put(route('admin.faqs.update', $faq), [
            'question'   => 'New question?',
            'answer'     => 'New answer.',
            'sort_order' => 2,
            'is_active'  => true,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('faqs', [
            'id'       => $faq->id,
            'question' => 'New question?',
        ]);
    }

    public function test_admin_can_delete_faq(): void
    {
        $this->loginAsAdmin();

        $faq = Faq::create([
            'question'   => 'Delete me?',
            'answer'     => 'Yes.',
            'sort_order' => 0,
            'is_active'  => true,
        ]);

        $this->delete(route('admin.faqs.destroy', $faq))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    // ===============================================================
    //  ADMIN SKILLS CRUD
    // ===============================================================

    public function test_admin_skills_index_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.skills'))->assertStatus(200);
    }

    public function test_admin_can_create_skill(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.skills.store'), [
            'name'       => 'Python',
            'level'      => 70,
            'years'      => 2,
            'category'   => 'Backend',
            'color'      => '#3776ab',
            'sort_order' => 99,
            'is_active'  => true,
            'in_orbit'   => false,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('skills', ['name' => 'Python']);
    }

    public function test_admin_skill_requires_name(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.skills.store'), [
            'level' => 50,
            'years' => 1,
        ])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_skill_requires_level(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.skills.store'), [
            'name'  => 'No Level',
            'years' => 1,
        ])
            ->assertSessionHasErrors('level');
    }

    public function test_admin_skill_requires_years(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.skills.store'), [
            'name'  => 'No Years',
            'level' => 50,
        ])
            ->assertSessionHasErrors('years');
    }

    public function test_admin_skill_level_must_be_within_range(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.skills.store'), [
            'name'  => 'Bad Level',
            'level' => 150,
            'years' => 1,
        ])
            ->assertSessionHasErrors('level');
    }

    public function test_admin_can_update_skill(): void
    {
        $this->loginAsAdmin();

        $skill = Skill::create([
            'name'       => 'Old Skill',
            'level'      => 50,
            'years'      => 1,
            'category'   => 'Misc',
            'sort_order' => 0,
            'is_active'  => true,
            'in_orbit'   => false,
        ]);

        $this->put(route('admin.skills.update', $skill), [
            'name'       => 'Updated Skill',
            'level'      => 80,
            'years'      => 3,
            'category'   => 'Dev',
            'color'      => '#ff0000',
            'sort_order' => 1,
            'is_active'  => true,
            'in_orbit'   => true,
        ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('skills', [
            'id'    => $skill->id,
            'name'  => 'Updated Skill',
            'level' => 80,
        ]);
    }

    public function test_admin_can_delete_skill(): void
    {
        $this->loginAsAdmin();

        $skill = Skill::create([
            'name'       => 'Deletable',
            'level'      => 50,
            'years'      => 1,
            'category'   => 'Misc',
            'sort_order' => 0,
            'is_active'  => true,
            'in_orbit'   => false,
        ]);

        $this->delete(route('admin.skills.destroy', $skill))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    // ===============================================================
    //  ADMIN OTHER PAGES
    // ===============================================================

    public function test_admin_content_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.content'))->assertStatus(200);
    }

    public function test_admin_messages_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.messages'))->assertStatus(200);
    }

    public function test_admin_newsletters_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.newsletters'))->assertStatus(200);
    }

    public function test_admin_password_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.password'))->assertStatus(200);
    }

    public function test_admin_intro_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.intro'))->assertStatus(200);
    }

    public function test_admin_founder_page_accessible(): void
    {
        $this->loginAsAdmin();
        $this->get(route('admin.founder'))->assertStatus(200);
    }

    // ===============================================================
    //  SEEDERS — MODELS WORK
    // ===============================================================

    public function test_site_setting_current_creates_defaults(): void
    {
        $this->assertDatabaseCount('site_settings', 0);

        $site = SiteSetting::current();

        $this->assertDatabaseCount('site_settings', 1);
        $this->assertEquals('Gany Labs', $site->brand_name);
    }

    public function test_intro_setting_current_creates_defaults(): void
    {
        $this->assertDatabaseCount('intro_settings', 0);

        $intro = IntroSetting::current();

        $this->assertDatabaseCount('intro_settings', 1);
        $this->assertEquals('Gany\'s Portofolio', $intro->name);
    }

    public function test_skill_check_and_seed_defaults_seeds_skills(): void
    {
        // Wipe the table so we start clean (some earlier tests may have
        // created individual skills that survived RefreshDatabase
        // because the controller calls checkAndSeedDefaults() which
        // commits records inside the same transaction).
        Skill::query()->delete();

        $this->assertDatabaseCount('skills', 0);

        Skill::checkAndSeedDefaults();

        $this->assertDatabaseCount('skills', 15);
        $this->assertDatabaseHas('skills', ['name' => 'Laravel']);
        $this->assertDatabaseHas('skills', ['name' => 'Figma']);
    }

    public function test_skill_check_and_seed_defaults_is_idempotent(): void
    {
        Skill::checkAndSeedDefaults();
        Skill::checkAndSeedDefaults(); // Run again

        $this->assertDatabaseCount('skills', 15);
    }

    // ===============================================================
    //  CONTACT MESSAGE MODEL
    // ===============================================================

    public function test_contact_message_can_be_created(): void
    {
        $msg = ContactMessage::create([
            'name'    => 'Test',
            'email'   => 'test@test.com',
            'message' => 'Testing',
            'subject' => 'Test subject',
        ]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'test@test.com']);
    }

    // ===============================================================
    //  NEWSLETTER MODEL
    // ===============================================================

    public function test_newsletter_can_be_created(): void
    {
        Newsletter::create(['email' => 'subscribe@test.com']);

        $this->assertDatabaseHas('newsletters', ['email' => 'subscribe@test.com']);
    }

    // ===============================================================
    //  PASSWORD UPDATE
    // ===============================================================

    public function test_admin_password_update_requires_current_password(): void
    {
        $this->loginAsAdmin();

        $this->put(route('admin.password.update'), [
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])
            ->assertSessionHasErrors('current_password');
    }

    public function test_admin_password_update_requires_min_8_chars(): void
    {
        $this->loginAsAdmin();

        $this->put(route('admin.password.update'), [
            'current_password'      => $this->getAdminPassword(),
            'password'              => 'short',
            'password_confirmation' => 'short',
        ])
            ->assertSessionHasErrors('password');
    }

    public function test_admin_password_update_requires_confirmation(): void
    {
        $this->loginAsAdmin();

        $this->put(route('admin.password.update'), [
            'current_password'      => $this->getAdminPassword(),
            'password'              => 'newpassword123',
            'password_confirmation' => 'different123',
        ])
            ->assertSessionHasErrors('password');
    }
}
