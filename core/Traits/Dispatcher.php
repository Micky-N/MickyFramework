<?php


namespace Core\Traits;


use Core\App;
use Core\Interfaces\EventInterface;
use Exception;
use ReflectionClass;

trait Dispatcher
{

    public static function dispatch($target = null, array $actions = [], array $params = [])
    {
        $class = new ReflectionClass(get_called_class());
        $event = $class->newInstance($target, $actions, $params);
        if(!($event instanceof EventInterface)){
            throw new Exception(sprintf("L'evenement %s doit implenter l'interface %s", $class->getName(), EventInterface::class));
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
            throw new Exception(sprintf("L'evenement %s n'est pas renseigné dans les prodivers", $class->getName()));
        }
    }
}