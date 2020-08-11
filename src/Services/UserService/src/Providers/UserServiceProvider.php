<?php

namespace ArtisanCloud\SaaSFramework\Services\UserService\src\Providers;

use Illuminate\Support\ServiceProvider;
use ArtisanCloud\SaaSFramework\Services\UserService\src\Contracts\UserServiceContract;
use ArtisanCloud\SaaSFramework\Services\UserService\src\UserService;

/**
 * Class UserServiceProvider
 * @package App\Providers
 */
class UserServiceProvider extends ServiceProvider
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
            UserServiceContract::class,
            UserService::class
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
//              $this->publishes([
//                  __DIR__ . '/../../config/user.php' => "/../" . config_path('user.php'),
//              ], ['SaaSFramework', 'User-Model']);

              // register artisan command
              if (! class_exists('CreateUserTable')) {
                $this->publishes([
                  __DIR__ . '/../../database/migrations/create_users_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_users_table.php'),
                  // you can add any number of migrations here
                ], ['SaaSFramework', 'User-Migration']);
              }
            }

    }
}
