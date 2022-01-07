<?php


namespace Core\Traits;


use Core\App;
use Core\Exceptions\Dispatcher\EventNotFoundException;
use Core\Exceptions\Dispatcher\EventNotImplementException;
use Core\Exceptions\Dispatcher\ListenerNotFoundException;
use Core\Exceptions\Dispatcher\ListenerNotImplementException;
use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;
use ReflectionClass;
use ReflectionException;

trait Dispatcher
{

    /**
     * Run events and listeners
     *
     * @param null $target
     * @param array|string $actions
     * @param array $params
     * @return EventInterface|object
     * @throws EventNotFoundException
     * @throws EventNotImplementException
     * @throws ListenerNotFoundException
     * @throws ListenerNotImplementException
     * @throws ReflectionException
     */
    public static function dispatch($target = null, $actions = null, array $params = [])
    {
        $class = new ReflectionClass(get_called_class());
        if(!is_array($actions)){
            $actions = [$actions];
        }
        $event = $class->newInstance($target, $actions, $params);
        if(!($event instanceof EventInterface)){
            throw new EventNotImplementException(sprintf("%s must implement %s interface", $class->getName(), EventInterface::class));
        }
        if(!in_array(null, $event->getActions())){
            if(!is_null(App::getListeners($class->getName()))){
                foreach ($event->getActions() as $action) {
                    if($event->isPropagationStopped()){
                        break;
                    }
                    $actionName = $action;
                    $action = class_exists($action) ? $action : App::getListenerActions($class->getName(), $action);
                    if(is_null($action)){
                        throw new ListenerNotFoundException(sprintf("%s doesn't have a listener for '%s' action in the EventServiceProvider", $class->getName(), $actionName));
                    }
                    $action = (new $action());
                    if(!($action instanceof ListenerInterface)){
                        throw new ListenerNotImplementException(sprintf("%s must implement %s interface", $actionName, ListenerInterface::class));
                    }
                    $action->handle($event);
                }
            } else {
                throw new EventNotFoundException(sprintf("%s is not defined in the EventServiceProvider", $class->getName()));
            }
        }
        return $event;
    }
}