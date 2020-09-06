<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\Account;
use App\Models\Code;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class CacheDriver implements Driver
{

    /**
     * @param mixed $code
     * @param int $expires seconds
     * @param Channel $channel
     * @param string $to
     * @param string $type
     * @return mixed
     */
    function setCode($code, int $expires, Channel $channel, string $to, $type = '')
    {
        return Cache::put($this->getCacheKey($channel, $to, $type), $code, Carbon::now()->addSeconds($expires));
    }

    function getCode(string $to, $type = '')
    {
        return Cache::get($this->getCacheKey($channel, $to, $type));
    }

    function canSend($throttles, Channel $channel, $to, $type = '')
    {
        return !Cache::has($this->getCacheKey($channel, $to, $type));
    }

    function getTo($code, $type = '')
    {

    }

    protected function getCacheKey(Channel $channel, string $to, $type)
    {
//        dd("verify-code:" . $type . ":" . $to->getCodeAddress($channel));
        return "verify-code:" . $type . ":" . $to->getCodeAddress($channel);
    }

}
