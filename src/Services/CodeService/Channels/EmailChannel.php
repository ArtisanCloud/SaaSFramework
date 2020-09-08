<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;

use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;

class EmailChannel implements Channel
{

    function send(string $to, $code, $options = [])
    {
        
    }

    function getIdentifier()
    {
        return 'email';
    }
}
