<?php


namespace Core\Traits;


use Core\App;
use Exception;
use ReflectionClass;

trait Dispatcher
{

    public static function dispatch($target = null, $params = [])
    {
        $class = new ReflectionClass(get_called_class());
        $event = $class->newInstance($target, $params);
        if(!is_null(App::getListeners($class->getName()))){
            foreach (App::getListeners($class->getName()) as $listener) {
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