<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\CodeService\Notifications;

use App\Services\HedgeService\src\Channels\SendAPIChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendInvitation extends Notification
{
    use Queueable;

    protected $m_code = '';

    /**
     * Create a new notification instance.
     *
     * @param mixed $code
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->m_code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SendAPIChannel::class];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toAPI($notifiable) : array
    {

        $view = view('artisan-cloud::invitation', [
            'code' => $this->m_code,
            'email' => $notifiable,
        ]);

        return $arrayBody = [
            "title" => "邀请函",
	        "content" => $view->render(),
	        "mailTo" => $notifiable,
        ];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
