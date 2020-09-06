<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Driver
{
    function setCode($code, int $expires, Channel $channel, string $to, $type = '');

    function getCode(string $to, $type = '');

    function canSend($throttles, Channel $channel, $to, $type = '');

    function getTo($code, $type = '');


}
