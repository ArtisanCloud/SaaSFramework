<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\TenantService\src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class TenantService
 * @package ArtisanCloud\SaaSFramework\Services\TenantService\src
 */
class TenantService extends Facade
{
    //
    protected static function getFacadeAccessor()
    {
        return TenantService::class;
    }
}
