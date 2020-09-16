<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\Account;
use App\Models\Code;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class CacheDriver implements Driver
{

    /**
     * @param mixed $code
     * @param int $expires seconds
     * @param string $to
     * @param string $type
     * @return mixed
     */
    function setCode($code, int $expires, string $to, $type = '')
    {
        return Cache::put($this->getCacheKey($to, $type), $code, Carbon::now()->addSeconds($expires));
    }

    function getCode(string $to, $type = '')
    {
        return Cache::get($this->getCacheKey($to, $type));
    }

    function canSend($throttles, $to, $type = '')
    {
        return !Cache::has($this->getCacheKey($to, $type));
    }

    function getTo($code, $type = '')
    {

    }

    protected function getCacheKey(string $to, $type)
    {
        return "verify-code:" . $type . ":" . $to;
    }

}
