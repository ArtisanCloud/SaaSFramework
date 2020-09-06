<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Channels;


use ArtisanCloud\SaaSFramework\Services\CodeService\Notifications\SendInvitation;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;

use Psr\Http\Message\ResponseInterface;

class SendAPIChannel implements Channel
{

    const SEND_API_EMAIL = 'https://mail-station.app.wangchaoyi.com/api/mail/send';
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $code
     * @return void
     */
    public function send(string $to, $code, $options = [])
    {
        $notification = new SendInvitation($code);
        $arrayBody = $notification->toAPI($to);
//        dd($arrayBody);
        // Send notification to the $notifiable instance...
        $response = $this->callAPI($arrayBody);
//        dd( $response->getStatusCode());
        return $response->getStatusCode()==Response::HTTP_OK;
    }

    protected function callAPI(array $arrayBody): ResponseInterface
    {
        $client = new Client();
        return $client->request('POST', self::SEND_API_EMAIL, [
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
