<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\Account;
use App\Models\VerifyCode;
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
     * @param string $to
     * @param string $type
     * @return mixed
     */
    function setVerifyCode($code, int $expires,  string $to, $type = '')
    {
        return Cache::put($this->getCacheKey($channel, $sendable, $type), $code, Carbon::now()->addSeconds($expires));
    }

    function getVerifyCode(string $to, $type = '')
    {
        return Cache::get($this->getCacheKey($channel, $sendable, $type));
    }

    function canSend($throttles, Channel $channel, $to, $type = '')
    {
        return !Cache::has($this->getCacheKey($channel, $sendable, $type));
    }

    function getTo($code, $type = '')
    {

    }
    protected function getCacheKey(Channel $channel, string $to, $type)
    {
//        dd("verify-code:" . $type . ":" . $sendable->getVerifyCodeAddress($channel));
        return "verify-code:" . $type . ":" . $sendable->getVerifyCodeAddress($channel);
    }

}
