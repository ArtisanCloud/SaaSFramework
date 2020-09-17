<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * UserResource: michaelhu
 * Date: 19/01/2017
 * Time: 9:30 PM
 */



use Carbon\Carbon;

use App\Models\User;
use App\Models\APIToken;

use App\Services\SalesforceService\Database\SalesforceEloquentFactory;
use Illuminate\Support\Str;

/*  security
**---***---***---***---***---***---***---***---***---***---**
*/



if (! function_exists('hashPassword')) {
	/**
	 * hash password.
	 *
	 * @param  string $strPlainPass
	 * @return string
	 */
	function hashPassword($strPlainPass){

		$encodedPassword = hash('sha256',$strPlainPass,false);
//		$encodedPassword = \Hash::make($strPlainPass);

		return $encodedPassword;

	}
}


if (! function_exists('encodeHashedPassword')) {
	/**
	 * Encode hashed password.
	 *
	 * @param  string $strHashedPassword
	 * @return string
	 */
	function encodeHashedPassword($strHashedPassword){

//		$strEncodedPassword = bcrypt($strHashedPassword);
		$strEncodedPassword = \Hash::make($strHashedPassword);

		return $strEncodedPassword;

	}
}


if (! function_exists('encodePlainPassword')) {
	/**
	 * Encode plain password.
	 *
	 * @param  string $strPlainPassword
	 * @return string
	 */
	function encodePlainPassword($strPlainPassword){

		$strHashPassword = hashPassword($strPlainPassword);
		$strEncodedPassword = encodeHashedPassword($strHashPassword);

		return $strEncodedPassword;

	}
}


if (! function_exists('getGuestSignature')) {
	/**
	 * signature for app guest request
	 *
	 * @param  string  $strDeviceUUID
	 * @param  string  $strQueryDate
	 * @return string
	 */
	function getGuestSignature($strDeviceUUID, $strQueryDate){

//		dump($strQueryDate);
		$dateQuery = new Carbon($strQueryDate);
//    dd($dateQuery->toDateTimeString());

		if(!is_null($dateQuery)){
			$strMerged = sprintf("%02d", $dateQuery->second)
						.sprintf("%02d", $dateQuery->minute)
						.sprintf("%02d", $dateQuery->hour)
						.'&'
						.$strDeviceUUID
						.'&'
						.sprintf("%02d", $dateQuery->day)
						.sprintf("%02d", $dateQuery->month)
						.sprintf("%02d", $dateQuery->year);

//       dump($strMerged);
			$signature = hash('sha256',$strMerged,false);

//       dd($signature);
		}else{
			return NULL;
		}

		return $signature;

	}
}



if (! function_exists('getLoginUserSignature')) {
    /**
     * signature for app login user request
     *
     * @param  string $strDeviceUUID
     * @param  string $strQueryDate
     * @return string
     */
    function getLoginUserSignature($strDeviceUUID, $strQueryDate, $strToken)
    {

        $dateQuery = new Carbon($strQueryDate);
//    dd($dateQuery->toDateTimeString());

        if (!is_null($dateQuery)) {
            $strMerged = sprintf("%02d", $dateQuery->second)
                . sprintf("%02d", $dateQuery->minute)
                . sprintf("%02d", $dateQuery->hour)
                . '&'
                . $strDeviceUUID
                . '&'
                . $strToken
                . '&'
                . sprintf("%02d", $dateQuery->day)
                . sprintf("%02d", $dateQuery->month)
                . sprintf("%02d", $dateQuery->year);

//       dd($strMerged);
            $signature = hash('sha256', $strMerged, false);

//       dd($signature);
        } else {
            return NULL;
        }

        return $signature;

    }

}

if (! function_exists('validateMobileFormat')) {
    /**
     * Validate mobile format
     *
     * @param  string $mobile
     * @return boolean
     */
    function validateMobileFormat($mobile)
    {
        return preg_match('/^1[3456789]\d{9}$/', $mobile );
    }

}


if (! function_exists('isJson')) {
    /**
     * Validate mobile format
     *
     * @param  string $string
     * @return boolean
     */
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}

/*  system
**---***---***---***---***---***---***---***---***---***---**
*/
if (! function_exists('guid')) {
    /**
     * Create a Global Unque ID
     *
     * @param  string $mx
     * @return strID
     */
    function guid($mx = 999999, $bUpper=false)
    {
        if($bUpper){
            return strtoupper(md5(microtime() . mt_rand(0, $mx)));
        }else{
            return strtolower(md5(microtime() . mt_rand(0, $mx)));
        }

    }

}


if (! function_exists('transformKeysToSnake')) {
    /**
     * Transform Array Keys to Snake format
     *
     * @param  string $mx
     * @return strID
     */
    function transformKeysToSnake(array $arrayData)
    {
        $arrayTransformedKeys = [];
        foreach ($data as $key => $value) {
            $arrayTransformedKeys[Str::snake($key)] = $value;
        }
        return $arrayTransformedKeys;
    }

}

if (! function_exists('transformArrayKeysToSnake')) {
    /**
     * Transform Array Keys to Snake format
     *
     * @param  string $mx
     * @return strID
     */
    function transformArrayKeysToSnake(array $arrayData)
    {
        $arrayTransformedKeys = [];
        foreach ($arrayData as $key => $value) {
            $arrayTransformedKeys[Str::snake($key)] = $value;
        }
        return $arrayTransformedKeys;
    }

}

if (! function_exists('transformArrayKeysToCamel')) {
    /**
     * Transform Array Keys to Camel format
     *
     * @param  string $mx
     * @return strID
     */
    function transformArrayKeysToCamel(array $arrayData)
    {
        $arrayTransformedKeys = [];
        foreach ($arrayData as $key => $value) {
            $arrayTransformedKeys[Str::camel($key)] = $value;
        }
        return $arrayTransformedKeys;
    }

}






