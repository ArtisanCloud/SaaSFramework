<?php

namespace ArtisanCloud\SaaSFramework\Services\LandlordService\src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LandlordService
 * @package ArtisanCloud\SaaSFramework\Services\LandlordService\src
 */
class LandlordService extends Facade
{
    //
    protected static function getFacadeAccessor()
    {
        return LandlordService::class;
    }
}
