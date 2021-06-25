<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Filesystem\Filesystem::macro('formatClearBase64String',function($string){
            if (strpos($string, ';base64') !== false) {
                list(, $string) = explode(';', $string);
                list(, $string) = explode(',', $string);
            }

            // strict mode filters for non-base64 alphabet characters
            if (base64_decode($string, true) === false) {
                return null;
            }

            // decoding and then reeconding should not change the data
            if (base64_encode(base64_decode($string)) !== $string) {
                return null;
            }

            return $string;
        });
    }
}
