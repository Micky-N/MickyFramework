<?php


namespace Core;


use Pusher\PushNotifications\PushNotifications;

class PusherBeams
{
    /**
     * @var PushNotifications
     */
    private PushNotifications $beamsClient;

    public function __construct()
    {
        $this->beamsClient = new PushNotifications(array(
            "instanceId" => _env('BEAMS_PUBLIC_KEY'),
            "secretKey" => _env('BEAMS_PRIVATE_KEY'),
        ));
    }

    public function send($notifiable, array $message)
    {
        return $this->beamsClient->publishToUsers([$notifiable->routeNotificationFor('beams')], [
            'web' => $message
        ]);
    }
}