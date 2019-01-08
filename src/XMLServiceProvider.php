<?php

namespace ACFBentveld\XML;

use Illuminate\Support\ServiceProvider;

class XMLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('XML', XML::class);
    }
}
