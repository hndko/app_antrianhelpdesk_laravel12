<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'components.layout',
            'components.display',
            'components.guest',
            'components.guest-layout',
        ], function ($view) {
            $defaultLogoUrl = asset('assets/helpdesk-logo-icon.svg');
            $defaultFaviconUrl = asset('assets/helpdesk-favicon.svg');

            $settings = null;

            try {
                if (Schema::hasTable('settings')) {
                    $settings = Setting::first();
                }
            } catch (\Throwable) {
                $settings = null;
            }

            $resolveUrl = function (?string $value, string $default): string {
                if (! $value) {
                    return $default;
                }

                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
                    return $value;
                }

                return asset($value);
            };

            $view->with('brand', [
                'title' => $settings->app_title ?? 'Service Display',
                'logo_url' => $resolveUrl($settings->logo_url ?? null, $defaultLogoUrl),
                'favicon_url' => $resolveUrl($settings->favicon_url ?? null, $defaultFaviconUrl),
            ]);
        });
    }
}
