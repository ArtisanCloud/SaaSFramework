<?php
/**
 * Created by PhpStorm.
 * UserResource: michaelhu
 * Date: 2019/7/9
 * Time: 5:30 PM
 */


return [

    API_RETURN_CODE_INIT => '',
    API_RETURN_CODE_WARNING => 'return warning',
    API_RETURN_CODE_ERROR => 'return error',

    API_RESULT_CODE_INIT => '',

    API_RESULT_CODE_SUCCESS_RESET_PASSWORD => 'Success',

    API_WARNING_CODE_IN_MAINTENANCE => 'System in maintenance',

    API_ERR_CODE_REQUEST_PARAMETER => 'invalid request parameter',
    API_ERR_CODE_MULTI_ERROR => 'multi errors here',
    API_ERR_CODE_UNAUTHENTICATED => 'unauthenticated',
    API_ERR_CODE_UNAUTHENTICATED_LOGIN => 'Incorrect username or password',
    API_ERR_CODE_UNAUTHENTICATED_ACTION => 'need password to auth',
    API_ERR_CODE_METHOD_NOT_ALLOWED => 'request method not allowed',

    API_ERR_CODE_TOKEN_NOT_MATCH_PROVIDER => 'cannot match token with provider',
    API_ERR_CODE_GRANT_TYPE_ERROR => 'grant type error',

    API_ERR_CODE_HEADER_PLATFORM => 'header lacks platform',
    API_ERR_CODE_HEADER_SOURCE => 'header lacks channel',
    API_ERR_CODE_HEADER_UUID => 'header lacks uuid',

    API_ERR_CODE_FAIL_TO_SEND_INVITATION_CODE => 'Failed to send invitation code',

    API_ERR_CODE_MOBILE_ALREADY_EXISTS => 'This phone number has been registered',
    API_ERR_CODE_USER_UNREGISTER => 'Unregistered user',
    API_ERR_CODE_ACCOUNT_UNREGISTER => 'Unregistered account',
    API_ERR_CODE_ACCOUNT_PASSWORD_INCORRECT => 'Account password is incorrect',
    API_ERR_CODE_ACCOUNT_FROZEN => 'Account has been frozen, please contact Space Service Support',
    API_ERR_CODE_MOBILE_UNREGISTER => 'Unregistered phone number',
    API_ERR_CODE_FAIL_TO_CREATE_USER => 'fail to create user',
    API_ERR_CODE_FAIL_TO_CREATE_USER_PROFILE => 'fail to create user profile',
    API_ERR_CODE_FAIL_TO_UPDATE_LOCALE => 'fail to update locale',
    
    API_ERR_CODE_SELF => "illegal operation",

    API_ERR_VALIDATION => 'validation errors',

];
