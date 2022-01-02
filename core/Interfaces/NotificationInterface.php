<?php


namespace Core\Interfaces;


interface NotificationInterface
{
    /**
     * Indique quelle système de notification
     * utiliser
     *
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable);
}