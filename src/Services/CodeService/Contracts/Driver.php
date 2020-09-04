<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Driver
{
    function setVerifyCode($code, int $expires, string $to, $type = '');

    function getVerifyCode(string $to, $type = '');

    function canSend($throttles, Channel $channel, $to, $type = '');

    function getTo($code, $type = '');


}
