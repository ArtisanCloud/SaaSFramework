<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;


use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;

use Illuminate\Support\Facades\Log;

class NoneChannel implements Channel
{

    function send(string $to, $code, $options = [])
    {
        return true;
    }

    function getIdentifier()
    {
        return 'develop';
    }
}
