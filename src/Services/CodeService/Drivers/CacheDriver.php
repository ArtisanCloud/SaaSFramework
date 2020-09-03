<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\Account;
use App\Models\VerifyCode;
use App\Services\CodeService\Contracts\Channel;
use App\Services\CodeService\Contracts\Driver;
use App\Services\CodeService\Contracts\Sendable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class CacheDriver implements Driver
{


    /** Verify code in Cache */


    /**
     * @param string $expire seconds
     * @param Sendable $sendable
     * @param string $label
     * @return mixed
     */
    function setVerifyCode($code, $expires, Channel $channel, Sendable $sendable, $label = '')
    {
        return Cache::put($this->getCacheKey($channel, $sendable, $label), $code, Carbon::now()->addSeconds($expires));
    }

    function getVerifyCode(Channel $channel, Sendable $sendable, $label = '')
    {
        return Cache::get($this->getCacheKey($channel, $sendable, $label));
    }

    function canSend($throttles, Channel $channel, Sendable $sendable, $label = '')
    {
        return !Cache::has($this->getCacheKey($channel, $sendable, $label));
    }

    function getSendable($code, $label = '')
    {

    }
    protected function getCacheKey(Channel $channel, Sendable $sendable, $label)
    {
//        dd("verify-code:" . $label . ":" . $sendable->getVerifyCodeAddress($channel));
        return "verify-code:" . $label . ":" . $sendable->getVerifyCodeAddress($channel);
    }

    /** QR code in Cache */
    function readQRCode($value, $label){

//        Log::info('key:'.$this->getCacheQRKey($value, $label));
//        Log::info('key get value: '.Cache::get($this->getCacheQRKey($value, $label)));
        return Cache::get($this->getCacheQRKey($value, $label));
    }

    function setQRCode($code, $expires, Channel $channel, Sendable $sendable, $label = '')
    {
        return Cache::put($this->getCacheQRKey($code, $label), $sendable->getVerifyCodeAddress($channel), Carbon::now()->addSeconds($expires));
    }

    public function getCacheQRKey($code, $label)
    {
        return "qr-code:" . $label . ":" . $code;
    }
}
