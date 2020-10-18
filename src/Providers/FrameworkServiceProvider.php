<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Providers;

use App\Http\Kernel;
use ArtisanCloud\SaaSFramework\Http\Middleware\{CheckLandlord, CheckUser, CheckHeader, CheckClientHavingUser};

use ArtisanCloud\SaaSFramework\Services\LandService\src\Providers\LandServiceProvider;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Providers\LandlordServiceProvider;
use ArtisanCloud\SaaSFramework\Services\TenantService\src\Providers\TenantServiceProvider;
use ArtisanCloud\SaaSFramework\Services\CodeService\Providers\CodeServiceProvider;

use Illuminate\Routing\Router;
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

        // config framework router
        $this->configRouter();

        // config search path for pprtgres
        $this->configPostgresSearchPath();

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/framework.php' => "/../" . config_path('artisancloud/framework.php'),
            ], ['ArtisanCloud', 'SaaSFramework', 'Landlord-Config']);
        }


    }

    public function configPostgresSearchPath()
    {
        $searchPath = config('database.connections.pgsql.search_path', 'public');
        \DB::connection('pgsql')->statement("SET search_path TO {$searchPath}");
    }

    public function configRouter()
    {
        // push middlewares
        $kernel = resolve(Kernel::class);
        $kernel->pushMiddleware(CheckLandlord::class);
        $kernel->pushMiddleware(CheckHeader::class);
        $kernel->pushMiddleware(CheckUser::class);
        $kernel->pushMiddleware(CheckClientHavingUser::class);

        // alias middlewares
        $router = resolve(Router::class);
        $router->aliasMiddleware('checkLandlord', CheckLandlord::class);
        $router->aliasMiddleware('checkHeader', CheckHeader::class);
        $router->aliasMiddleware('checkUser', CheckUser::class);
        $router->aliasMiddleware('checkClientHavingUser', CheckClientHavingUser::class);


        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

    }
}
