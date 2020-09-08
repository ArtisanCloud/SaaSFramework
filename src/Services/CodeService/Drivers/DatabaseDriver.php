<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Drivers;


use App\Models\User;
use ArtisanCloud\SaaSFramework\Services\CodeService\Models\Code;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;

use Carbon\Carbon;

class DatabaseDriver implements Driver
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
        $mdlCode = Code::create([
            "code" => $code,
            "to" => $to,
            "type" => $type,
            "status" => Code::STATUS_NORMAL,
            "expired_at" => Carbon::now()->addSeconds($expires)
        ]);
//        dd($mdlCode);
        return $mdlCode->code;
    }

    function getCode(string $to, $type = '')
    {
        $verifyCode = Code::where('to', $to)
            ->where('type', $type)
            ->where('status', Code::STATUS_NORMAL)
            ->where('expired_at', '>=', Carbon::now())
            ->latest()
            ->first();
        return $verifyCode ? $verifyCode->code : null;
    }

    function canSend($throttles, Channel $channel, $to, $type = '')
    {
        return !Code::where('to', $to)
            ->where('status', Code::STATUS_NORMAL)
            ->where('type', $type)
            ->where('created_at', '>=', Carbon::now()
                ->subSeconds($throttles))
            ->exists();
    }

    function getTo($code, $type = '')
    {
        $verifyCode = Code::where('code', $code)
            ->where('type', $type)
            ->latest()
            ->first();

        return User::findByPhone($verifyCode->mobile);
    }


}
