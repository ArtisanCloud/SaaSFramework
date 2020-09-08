<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Channel
{
    function send(string $to, $code, $options = []);

    function getIdentifier();
}
