<?php
/**
 * Created by PhpStorm.
 * UserResource: michaelhu
 * Date: 2019/7/9
 * Time: 5:30 PM
 */


return [

    API_RETURN_CODE_INIT => '',
    API_RETURN_CODE_WARNING => '警告信息',
    API_RETURN_CODE_ERROR => '错误信息',

    API_RESULT_CODE_INIT => '',

    API_RESULT_CODE_SUCCESS_RESET_PASSWORD => '密码修改成功',

    API_WARNING_CODE_IN_MAINTENANCE => '当前系统正在维护',

    API_ERR_CODE_REQUEST_PARAMETER => '请求参数错误',
    API_ERR_CODE_MULTI_ERROR => '请看data详情',
    API_ERR_CODE_UNAUTHENTICATED => '未获得授权',
    API_ERR_CODE_UNAUTHENTICATED_LOGIN => '用户名或密码不正确',
    API_ERR_CODE_UNAUTHENTICATED_ACTION => '需要身份登陆访问',
    API_ERR_CODE_METHOD_NOT_ALLOWED => '请求的Method方法错误',

    API_ERR_CODE_TOKEN_NOT_MATCH_PROVIDER => 'token与provider不匹配',
    API_ERR_CODE_GRANT_TYPE_ERROR => '授权类型无效',

    API_ERR_CODE_HEADER_PLATFORM => '头请求数据缺少平台信息',
    API_ERR_CODE_HEADER_SOURCE => '头请求数据缺少渠道信息',
    API_ERR_CODE_HEADER_UUID => '头请求数据缺唯一标示信息',

    API_ERR_CODE_SELF => "违规操作",


    API_ERR_CODE_FAIL_TO_CREATE_VERIFY_CODE => '创建验证码失败',
    API_ERR_CODE_FAIL_TO_SEND_SMS => '发送短信失败',
    API_ERR_CODE_FAIL_TO_SEND_VERIFY_CODE => '发送确认短信失败',
    API_ERR_CODE_VERIFY_CODE_REQUEST_DUPLICATED => '1分钟内，重复请求验证码',
    API_ERR_CODE_INVALID_VERIFY_CODE => '验证码错误',
    API_ERR_CODE_EXPIRED_VERIFY_CODE => '验证码已过期',

];
