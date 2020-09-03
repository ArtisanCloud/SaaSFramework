<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Channel
{
    function send(Sendable $sendable, $code, $options = []);

    function getIdentifier();
}
