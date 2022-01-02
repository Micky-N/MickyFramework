<?php


namespace Core;


use Core\Interfaces\NotificationInterface;
use Core\Traits\Notify;
use Exception;
use ReflectionClass;
use ReflectionException;

class Notification
{
    /**
     * Envoi la notification à tous
     * les utilisateurs séléctionnés
     *
     * @param array $notifiables
     * @param NotificationInterface $notification
     * @throws ReflectionException
     */
    public static function send(array $notifiables, NotificationInterface $notification)
    {
        foreach ($notifiables as $notifiable){
            $usingTrait = in_array(
                Notify::class,
                array_keys((new ReflectionClass(get_class($notifiable)))->getTraits())
            );
            if($usingTrait == true){
                $notifiable->notify($notification);
            }else{
                throw new Exception(sprintf('Le model %s doit utiliser le trait %s', get_class($notifiable), Notify::class));
            }
        }
    }
}