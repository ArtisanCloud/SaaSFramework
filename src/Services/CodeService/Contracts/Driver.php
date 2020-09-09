<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Driver
{
    function setCode($code, int $expires, string $to, $type = '');

    function getCode(string $to, $type = '');

    function canSend($throttles, $to, $type = '');

    function getTo($code, $type = '');


}
