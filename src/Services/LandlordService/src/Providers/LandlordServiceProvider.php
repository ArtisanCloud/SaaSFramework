<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\LandlordService\src\Providers;

use Illuminate\Support\ServiceProvider;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Contracts\LandlordServiceContract;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\LandlordService;
use Laravel\Passport\Passport;

/**
 * Class LandlordServiceProvider
 * @package App\Providers
 */
class LandlordServiceProvider extends ServiceProvider
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
            LandlordServiceContract::class,
            LandlordService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // make sure passport is installed
        $this->configPassport();

        if ($this->app->runningInConsole()) {
            // publish config file
            $this->publishes([
                __DIR__ . '/../../config/landlord.php' => "/../" . config_path('artisancloud/landlord.php'),
            ], ['ArtisanCloud', 'SaaSFramework', 'Landlord-Config']);

            // register artisan command
            if (!class_exists('CreateLandlordTable')) {
                $this->publishes([
                    __DIR__ . '/../../database/migrations/create_landlords_table.php' => database_path('migrations/0_0_0_0_create_landlords_table.php'),
                    // you can add any number of migrations here
                ], ['ArtisanCloud', 'SaaSFramework', 'Landlord-Migration']);
            }
        }

    }

    protected function configPassport()
    {
        // make sure passport is installed
        Passport::routes(function ($router) {
            // RouteRegistrar->forAccessTokens()
            $router->forAccessTokens();
        }, ['middleware' => 'checkHeader']);
        Passport::tokensExpireIn(now()->addDays(90));
        Passport::refreshTokensExpireIn(now()->addDays(90));

    }
}
