<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;


use App\Services\HedgeService\src\Notifications\SendInvitation;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Sendable;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

use Illuminate\Notifications\Notification;

use Psr\Http\Message\ResponseInterface;

class SendAPIChannel implements Channel
{

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $code
     * @return void
     */
    public function send(Sendable $sendable, $code, $options = [])
    {
        $notification = new SendInvitation($code);
        $arrayBody = $notification->toAPI($sendable);
//        dd($arrayBody);
        // Send notification to the $notifiable instance...
        $response = $this->callAPI($arrayBody);
//        dump($response->getBody());
    }

    protected function callAPI(array $arrayBody): ResponseInterface
    {
        $client = new Client();
        return $client->request('POST', 'https://mail-station.app.wangchaoyi.com/api/mail/send', [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            RequestOptions::JSON => $arrayBody
        ]);
    }

    function getIdentifier()
    {
        return 'api';
    }
}
