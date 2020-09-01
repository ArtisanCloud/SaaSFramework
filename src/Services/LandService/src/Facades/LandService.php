<?php

namespace ArtisanCloud\SaaSFramework\Services\LandService\src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LandService
 * @package ArtisanCloud\SaaSFramework\Services\LandService\src
 */
class LandService extends Facade
{
    //
    protected static function getFacadeAccessor()
    {
        return LandService::class;
    }
}
