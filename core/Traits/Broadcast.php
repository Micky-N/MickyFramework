<?php


namespace Core\Traits;


use Core\App;
use Core\Interfaces\NotificationInterface;
use Exception;
use ReflectionClass;

trait Broadcast
{
    public static function notify($target = null, array $actions = [], array $params = [])
    {
        $notificationRef = new ReflectionClass(App::Providers('notification'));
        $notification = $notificationRef->newInstance();
        if(!($notification instanceof NotificationInterface)){
            throw new Exception(sprintf("La classe %s doit implémenter l'interface %s", $notificationRef->getName(), NotificationInterface::class));
        }
        if(!is_null(App::getListeners($class->getName()))){
            foreach ($actions as $action) {
                $listener = App::getListenerActions($class->getName(), $action);
                if(is_null($listener)){
                    throw new Exception(sprintf("L'event %s n'a pas de listener pour l'action %s dans les prodivers", $class->getName(), $action));
                }
                if($event->isPropagationStopped()){
                    break;
                }
                (new $listener())->handle($event);
            }
        } else {
            throw new Exception(sprintf("L'event %s n'est pas renseigné dans les prodivers", $class->getName()));
        }
    }
}