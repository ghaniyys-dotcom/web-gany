<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // URL::forceScheme('https'); // disabled for local dev

        View::share('showAdminLink', (bool) config('site.show_admin_link', false));
    }
}
