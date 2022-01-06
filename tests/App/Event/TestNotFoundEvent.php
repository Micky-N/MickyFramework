<?php


namespace Tests\App\Event;


use Core\Interfaces\EventInterface;
use Core\Traits\Dispatcher;

class TestNotFoundEvent implements EventInterface
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
        $this->actions = $actions;
        $this->params = $params;
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

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    public function getParam(string $key)
    {
        return $this->params[$key];
    }
}