<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Providers;

use ArtisanCloud\SaaSFramework\Services\LandService\src\Providers\LandServiceProvider;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Providers\LandlordServiceProvider;
use ArtisanCloud\SaaSFramework\Services\TenantService\src\Providers\TenantServiceProvider;
use ArtisanCloud\SaaSFramework\Services\CodeService\Providers\CodeServiceProvider;

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
        $this->app->register(CodeServiceProvider::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/framework.php' => "/../" . config_path('artisancloud/framework.php'),
            ], ['ArtisanCloud', 'SaaSFramework', 'Landlord-Config']);
        }

        // config search path for pprtgres
        $this->configPostgresSearchPath();

    }

    public function configPostgresSearchPath()
    {
        $searchPath = config('database.connections.pgsql.search_path');
        \DB::connection('pgsql')->statement("SET search_path TO {$searchPath}");
    }
}
