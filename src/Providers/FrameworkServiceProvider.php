<?php

namespace ArtisanCloud\SaaSFramework\Providers;

use ArtisanCloud\SaaSFramework\Services\LandService\src\Providers\LandServiceProvider;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Providers\LandlordServiceProvider;
use ArtisanCloud\SaaSFramework\Services\TenantService\src\Providers\TenantServiceProvider;
use ArtisanCloud\SaaSFramework\Services\CodeService\Providers\VerifyCodeServiceProvider;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

/**
 * Class FrameworkServiceProvider
 * @package ArtisanCloud\SaaSFramework\Providers
 */
class FrameworkServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->register(LandServiceProvider::class);
        $this->app->register(LandlordServiceProvider::class);
        $this->app->register(TenantServiceProvider::class);
        $this->app->register(VerifyCodeServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'artisan-cloud');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/framework.php' => "/../" . config_path('artisancloud/framework.php'),
            ], ['SaaSFramework', 'Landlord-Config']);
        }

    }
}
