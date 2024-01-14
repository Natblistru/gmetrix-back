<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        Validator::extend('audio_type', function ($attribute, $value, $parameters, $validator) {
            return in_array($value->getMimeType(), ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/mp3']);
        });
    }
}
