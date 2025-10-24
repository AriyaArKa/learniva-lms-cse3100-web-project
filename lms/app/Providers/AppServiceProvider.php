<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\Models\SmtpSetting;

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
            Config::set('mail',$data);
           }
       } // end if
    }
}
