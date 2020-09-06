<?php

namespace ArtisanCloud\SaaSFramework\Services\CodeService\Models;

use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use Carbon\Carbon;

class Code extends ArtisanCloudModel
{
    //
    const TYPE_INVTATION = 1;
    const TYPE_VERIFY = 2;

    const VERIFY_CODE_REGISTER = 1;
    const VERIFY_CODE_FORGET = 2;
    const VERIFY_CODE_UPDATE = 3;
    const VERIFY_CODE_IDENTIFY = 4;
    const VERIFY_CODE_CHECK_IN = 5;

    protected $table = 'codes';

    // white list for mass assign
    protected $fillable = ["code", "to", "type", "status", "expired_at"];


    public static function exitsCodeSentToMobile($mobile)
    {
        $strSQL = Code::where([
            "mobile" => $mobile,
            "status" => self::STATUS_NORMAL,
        ])->where("expired_at", '>', Carbon::now());

//        dd($strSQL->toSql());

        $count = $strSQL->count();
//        dd($count);

        return $count > 0;
    }


    public static function createCode($mobile, $type = self::VERIFY_CODE_REGISTER)
    {

        $dNow = Carbon::now();
        $dExpiredAt = Carbon::now()->addMinute(5);

        $iCode = rand(10000, 99999);
        $bResult = Code::create([
            "code" => $iCode,
            "mobile" => $mobile,
            "type" => $type,
            "status" => self::STATUS_NORMAL,
            "expired_at" => $dExpiredAt,
            "created_at" => $dNow,
        ])->usesTimestamps();

        if ($bResult) {
            return $iCode;
        } else {
            return $bResult;
        }
    }


    public static function getCode($code, $mobile = null)
    {

        $condition = [
            'code' => $code,
            'status' => self::STATUS_NORMAL
        ];

        if ($mobile) {
            $condition['mobile'] = $mobile;
        }

        $vCode = Code::where($condition)->first();

        return $vCode;
    }

    public function isExpired()
    {

        $bResult = false;

        $now = new Carbon();
        $expiredAt = new Carbon($this->expired_at);

        $differenceSeconds = $now->diffInSeconds($expiredAt, false);
//			dump($differenceSeconds);
        if ($differenceSeconds < 0) {
            $bResult = true;
        }

//        dump($now,$expiredAt,$bResult);
        return $bResult;

    }

    public function scopeUnexpiredPhoneCode($query, $phone, $label)
    {
        return $query->where('mobile', $phone)->where('status', ArtisanCloudModel::STATUS_NORMAL)->where('type', $label)->where('expired_at', '>=', Carbon::now());
    }

}
