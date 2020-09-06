<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Facades;

use App\Services\CodeService\Channels\NoneChannel;
use App\Services\CodeService\Drivers\CacheDriver;
use App\Services\CodeService\Testing\CodeServiceFake;
use Illuminate\Support\Facades\Facade;

class CodeService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\CodeService\CodeService::class;
    }

    public static function fake($mobile, $code)
    {
        CodeServiceFake::$verifycodes[$mobile] = $code;
        static::swap($fake = new CodeServiceFake(new CacheDriver(), new NoneChannel()));

        return $fake;
    }
}
