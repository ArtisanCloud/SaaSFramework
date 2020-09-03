<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Sendable
{
    function getVerifyCodeAddress(Channel $channel);
}
