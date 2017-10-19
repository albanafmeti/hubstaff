<?php

namespace Noisim\Hubstaff;

use Illuminate\Support\ServiceProvider;

class HubstaffServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/hubstaff.php' => config_path('hubstaff.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("hubstaff", \Noisim\Hubstaff\Entities\Hubstaff::class);
        $this->app->bind("hs-user", \Noisim\Hubstaff\Entities\User::class);
        $this->app->bind("hs-organization", \Noisim\Hubstaff\Entities\Organization::class);
        $this->app->bind("hs-project", \Noisim\Hubstaff\Entities\Project::class);
    }
}