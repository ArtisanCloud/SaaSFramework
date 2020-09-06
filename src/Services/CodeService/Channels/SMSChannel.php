<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;


use App\Facades\SMSAliyun;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;


class SMSChannel implements Channel
{

    function send(string $to, $code, $options = [])
    {
        if (!isset($options['template'])) {
            throw new \Exception("template 参数不能为空");
        }
        if (!isset($options['sign'])) {
            throw new \Exception("sign 参数不能为空");
        }
        $templatePara = [
            'code' => $code,
            'product' => 'Space'
        ];
        $optionTemplatePara = isset($options['template_para']) ? $options['template_para'] : [];
        return SMSAliyun::sendSMS($to->getCodeAddress($this), $options['sign'], $options['template'], array_merge($templatePara, $optionTemplatePara));
    }

    function getIdentifier()
    {
        return 'sms';
    }
}
