<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\User;
use ArtisanCloud\SaaSFramework\Services\CodeService\Models\VerifyCode;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;

use Carbon\Carbon;

class DatabaseDriver implements Driver
{

    /**
     * @param mixed $code
     * @param int $expires seconds
     * @param string $to
     * @param string $type
     * @return mixed
     */
    function setVerifyCode($code, int $expires, string $to, $type = '')
    {
        return VerifyCode::create([
            "code" => $code,
            "to" => $to,
            "type" => $type,
            "status" => VerifyCode::STATUS_NORMAL,
            "expired_at" => Carbon::now()->addSeconds($expires)
        ])->code;
    }

    function getVerifyCode(string $to, $type = '')
    {
        $verifyCode = VerifyCode::where('to', $to)
            ->where('type', $type)
            ->where('status', SpaceModel::STATUS_NORMAL)
            ->where('expired_at', '>=', Carbon::now())
            ->latest()
            ->first();
        return $verifyCode ? $verifyCode->code : null;
    }

    function canSend($throttles, Channel $channel, $to, $type = '')
    {
        return !VerifyCode::where('to', $to)
            ->where('status', SpaceModel::STATUS_NORMAL)
            ->where('type', $type)
            ->where('created_at', '>=', Carbon::now()
                ->subSeconds($throttles))
            ->exists();
    }

    function getTo($code, $type = '')
    {
        $verifyCode = VerifyCode::where('code', $code)
            ->where('type', $type)
            ->latest()
            ->first();

        return User::findByPhone($verifyCode->mobile);
    }


}
