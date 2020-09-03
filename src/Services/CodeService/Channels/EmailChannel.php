<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;

use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use AArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Sendable;

class EmailChannel implements Channel
{

    function send(Sendable $sendable, $code, $options = [])
    {
        
    }

    function getIdentifier()
    {
        return 'email';
    }
}
