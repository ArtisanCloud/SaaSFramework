<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Facades;

use App\Services\CodeService\Channels\NoneChannel;
use App\Services\CodeService\Drivers\CacheDriver;
use App\Services\CodeService\Testing\VerifyCodeServiceFake;
use Illuminate\Support\Facades\Facade;

class VerifyCodeService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\CodeService\VerifyCodeService::class;
    }

    public static function fake($mobile, $code)
    {
        VerifyCodeServiceFake::$verifycodes[$mobile] = $code;
        static::swap($fake = new VerifyCodeServiceFake(new CacheDriver(), new NoneChannel()));

        return $fake;
    }
}
