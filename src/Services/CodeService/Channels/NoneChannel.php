<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;


use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Sendable;
use Illuminate\Support\Facades\Log;

class NoneChannel implements Channel
{

    function send(Sendable $sendable, $code, $options = [])
    {
        return true;
    }

    function getIdentifier()
    {
        return 'develop';
    }
}
