<?php

namespace App\Notifications;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Psr\Http\Message\ResponseInterface;

class VerifyMailJP extends VerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::get('mail.verification.subject'))
            ->line(Lang::get('mail.verification.line_01'))
            ->action(Lang::get('mail.verification.action'), $verificationUrl)
            ->line(Lang::get('mail.verification.line_02'));
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

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $oauth = \DB::table('oauth_clients')->first();

        $client = new Client([
            'headers' => [
                'Accept' => 'application/json; charset=utf-8'
            ]
        ]);

        /**
         * @var $res ResponseInterface
         */
        $app_url = env('APP_URL');
        if (env('APP_ENV') === 'local') {
            $app_url = 'http://localhost';
        }

        $res = $client->request('POST',  $app_url . '/oauth/token', [
            'json' => [
                "grant_type" => "client_credentials",
                "client_id" => $oauth->id,
                "client_secret" => $oauth->secret,
                "scope" => "*"
            ],
            'verify' => false
        ]);

        $token = json_decode($res->getBody()->getContents(), true);

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'token' => $token['access_token']
            ]
        );
    }
}
