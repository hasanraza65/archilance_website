<?php

namespace App\Providers;

use App\Models\Service;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    public function boot(): void
    {
        // Shared with every view so partials never have to be passed the basics.
        View::composer('*', function ($view) {
            $view->with('s', app(SiteSettings::class));
        });

        View::composer(['layouts.front', 'partials.*'], function ($view) {
            $view->with('navServices', Service::published()->ordered()->get());
        });
    }
}
