<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\User;
use ArtisanCloud\SaaSFramework\Services\CodeService\Models\VerifyCode;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Sendable;
use Carbon\Carbon;

class DatabaseDriver implements Driver
{

    /**
     * @param string $expire seconds
     * @param Sendable $sendable
     * @param string $type
     * @return mixed
     */
    function setVerifyCode($code, $expires, Channel $channel, Sendable $sendable, $type = '')
    {
        return VerifyCode::create([
            "code" => $code,
            "mobile" => $sendable->getVerifyCodeAddress($channel),
            "type" => $type,
            "status" => VerifyCode::STATUS_NORMAL,
            "expired_at" => Carbon::now()->addSeconds($expires)
        ])->code;
    }

    function getVerifyCode(Channel $channel, Sendable $sendable, $type = '')
    {
        $verifyCode = VerifyCode::where('mobile', $sendable->getVerifyCodeAddress($channel))->where('type', $type)->where('status', SpaceModel::STATUS_NORMAL)->where('expired_at', '>=', Carbon::now())->latest()->first();
        return $verifyCode ? $verifyCode->code : null;
    }

    function canSend($throttles, Channel $channel, Sendable $sendable, $type = '')
    {
        return !VerifyCode::where('mobile', $sendable->getVerifyCodeAddress($channel))->where('status', SpaceModel::STATUS_NORMAL)->where('type', $type)->where('created_at', '>=', Carbon::now()->subSeconds($throttles))->exists();
    }

    function getSendable($code, $type = '')
    {
        $verifyCode = VerifyCode::where('code', $code)->where('type', $type)->latest()->first();

        return User::findByPhone($verifyCode->mobile);
    }

    function setQRCode($code, $expires, Channel $channel, Sendable $sendable, $type = ''){

    }
    function readQRCode($value, $type){

    }
}
