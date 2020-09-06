<?php

namespace ArtisanCloud\SaaSFramework\Services\CodeService\Providers;

use ArtisanCloud\SaaSFramework\Services\CodeService\Channels\SendAPIChannel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Channels\NoneChannel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Channels\SMSChannel;

use ArtisanCloud\SaaSFramework\Services\CodeService\Drivers\DatabaseDriver;
use ArtisanCloud\SaaSFramework\Services\CodeService\Drivers\CacheDriver;

use ArtisanCloud\SaaSFramework\Services\CodeService\InvitationCodeService;
use ArtisanCloud\SaaSFramework\Services\CodeService\CodeService;

use Illuminate\Support\ServiceProvider;

class CodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CodeService::class, function ($app) {
            $channel = SMSChannel::class;
            $driver = DatabaseDriver::class;
//            if ($this->app->environment('local', 'development', 'ci', 'app-review')) {
            if (config('artisancloud.framework.verify_code_channel','sms') == 'none') {
                $channel = NoneChannel::class;
                $driver = CacheDriver::class;
            }
            return new CodeService($app[$driver], $app[$channel]);
        });

        $this->app->singleton(InvitationCodeService::class, function ($app) {
            $channel = SendAPIChannel::class;
            $driver = DatabaseDriver::class;
            if (config('artisancloud.framework.invitation_code_channel','api') == 'none') {
                $channel = NoneChannel::class;
                $driver = CacheDriver::class;
            }
            return new InvitationCodeService($app[$driver], $app[$channel]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if ($this->app->runningInConsole()) {

//            $this->publishes([
//                __DIR__ . '/../../config/*.php' => "/../" . config_path('artisancloud/*.php'),
//            ], ['SaaSFramework', 'Landlord-Config']);

            // register artisan command
            if (! class_exists('CreateCodesTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_verify_codes_table.php' => database_path('migrations/2020_08_01_000040_create_codes_table.php'),
                    // you can add any number of migrations here
                ], ['SaaSFramework', 'Verify-Code-Migration']);
            }
        }
    }
}
