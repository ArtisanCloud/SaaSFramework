<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * UserResource: michaelhu
 * Date: 2019/1/15
 * Time: 4:00 PM
 */


const API_RETURN_CODE_INIT = 200;
const API_RETURN_CODE_WARNING = 300;
const API_RETURN_CODE_ERROR = 400;
const API_RETURN_CODE_NOT_FOUND = 404;


//---------------------------------------------


const API_RESULT_CODE_INIT = 0;


const API_RESULT_CODE_SUCCESS_RESET_PASSWORD = 101001;


//---------------------------------------------
const API_WARNING_CODE_IN_MAINTENANCE = 300503;


//---------------------------------------------

const API_ERR_CODE_REQUEST_PARAMETER = 40001;
const API_ERR_CODE_HEADER_PLATFORM = 400011;
const API_ERR_CODE_HEADER_SOURCE = 400012;
const API_ERR_CODE_HEADER_UUID = 400013;

const API_ERR_CODE_MULTI_ERROR = 40002;
const API_ERR_CODE_UNAUTHENTICATED = 40021;
const API_ERR_CODE_UNAUTHENTICATED_LOGIN = 400211;
const API_ERR_CODE_UNAUTHENTICATED_ACTION = 40022;
const API_ERR_CODE_METHOD_NOT_ALLOWED = 40023;


const API_ERR_CODE_TOKEN_NOT_MATCH_PROVIDER = 40010;
const API_ERR_CODE_GRANT_TYPE_ERROR = 40011;
const API_ERR_CODE_TOKEN_REVOKED = 40012;


const API_ERR_CODE_FAIL_TO_CREATE_VERIFY_CODE = 40101;
const API_ERR_CODE_FAIL_TO_SEND_SMS = 40102;
const API_ERR_CODE_FAIL_TO_SEND_VERIFY_CODE = 401021;
const API_ERR_CODE_VERIFY_CODE_REQUEST_DUPLICATED = 40103;
const API_ERR_CODE_INVALID_VERIFY_CODE = 401031;
const API_ERR_CODE_EXPIRED_VERIFY_CODE = 401032;
const API_ERR_CODE_REQUEST_PARAM_ERROR = 401032;

const API_ERR_CODE_FAIL_TO_CREATE_ARTISAN = 4010400;
const API_ERR_CODE_FAIL_TO_CREATE_USER = 4010401;
const API_ERR_CODE_FAIL_TO_CREATE_USER_PROFILE = 401041;
const API_ERR_CODE_FAIL_TO_UPDATE_ACCOUNT_INFO = 4010412;
const API_ERR_CODE_FAIL_TO_UPLOAD_ACCOUNT_ID = 4010413;
const API_ERR_CODE_FAIL_TO_UPDATE_LOCALE = 401042;
const API_ERR_CODE_USER_LACK_MOBILE = 401043;
const API_ERR_CODE_MOBILE_ALREADY_EXISTS = 401044;
const API_ERR_CODE_EMAIL_ALREADY_EXISTS = 4010445;
const API_ERR_CODE_USER_ALREADY_EXISTS = 4010446;
const API_ERR_CODE_USER_UNREGISTER = 4010447;
const API_ERR_CODE_ACCOUNT_UNREGISTER = 40104471;
const API_ERR_CODE_ACCOUNT_PASSWORD_INCORRECT = 40104472;
const API_ERR_CODE_ACCOUNT_FROZEN = 40104473;
const API_ERR_CODE_MOBILE_UNREGISTER = 4010448;
const API_ERR_CODE_EMAIL_UNREGISTER = 4010449;
const API_ERR_CODE_COUPON_EXCHANGE_FAIL = 40104450;
const API_ERR_CODE_CAMPAIGN_NOT_EXISTS = 40104452;

const API_ERR_VALIDATION = 404000;

const API_ERR_CODE_SELF = 80000;

