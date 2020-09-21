<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\TenantService\src\Models;

use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class Tenant extends ArtisanCloudModel
{
    protected $connection = 'tenant';


}
