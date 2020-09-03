<?php

namespace ArtisanCloud\SaaSFramework\Services\LandService\src\Providers;

use Illuminate\Support\ServiceProvider;
use ArtisanCloud\SaaSFramework\Services\LandService\src\Contracts\LandServiceContract;
use ArtisanCloud\SaaSFramework\Services\LandService\src\LandService;

/**
 * Class LandServiceProvider
 * @package App\Providers
 */
class LandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            LandServiceContract::class,
            LandService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
              // publish config file
              $this->publishes([
                  __DIR__ . '/../../config/land.php' => "/../" . config_path('artisancloud/land.php'),
              ], ['SaaSFramework', 'Land-Config']);


              // register artisan command
              if (! class_exists('CreateLandTable')) {
                $this->publishes([
                  __DIR__ . '/../../database/migrations/create_lands_table.php' => database_path('migrations/2020_08_01_000010_create_lands_table.php'),
                  // you can add any number of migrations here
                ], ['SaaSFramework', 'Land-Migration']);
              }
            }

    }
}
