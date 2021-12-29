<?php


namespace App\Events;

use Core\Interfaces\EventInterface;
use Core\Traits\Dispatcher;

class CategoryEvent implements EventInterface
{
    use Dispatcher;

    /**
     * @var mixed
     */
    private $target;
    private array $params = [];
    private bool $propagationStopped = false;
    private array $actions;

    /**
     * Event constructor.
     * @param mixed $target
     * @param array $actions
     * @param array $params
     */
    public function __construct($target, array $actions, array $params = [])
    {
        $this->target = $target;
        $this->params = $params;
        $this->actions = $actions;
    }

    /**
     * @param $flag
     */
    public function stopPropagation($flag)
    {
        $this->propagationStopped = $flag;
    }

    /**
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}