<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;

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
        // Register Image facade alias
        if (!class_exists('Image')) {
            class_alias(\Intervention\Image\Laravel\Facades\Image::class, 'Image');
        }

        // Share site settings with all frontend dashboard views
        View::composer('frontend.dashboard.*', function ($view) {
            $setting = SiteSetting::first();
            $view->with('setting', $setting);
        });

        if (Schema::hasTable('smtp_settings')) {
            $smtpsetting = SmtpSetting::first();

            if ($smtpsetting) {
                $data = [
                    'driver' => $smtpsetting->mailer,
                    'host' => $smtpsetting->host,
                    'port' => $smtpsetting->port,
                    'username' => $smtpsetting->username,
                    'password' => $smtpsetting->password,
                    'encryption' => $smtpsetting->encryption,
                    'from' => [
                        'address' => $smtpsetting->from_address,
                        'name' => 'Easycourselms'
                    ]

                ];
                Config::set('mail', $data);
            }
        } // end if
    }
}
