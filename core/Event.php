<?php


namespace Core;


use Core\Traits\Dispatcher;
use Core\Interfaces\EventInterface;

class Event implements EventInterface
{
    use Dispatcher;

    /**
     * @var mixed
     */
    protected $target;
    protected array $params = [];
    private bool $propagationStopped = false;


    public function __construct($target, array $params)
    {
        $this->target = $target;
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params)
    {
        return $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function stopPropagation($flag)
    {
        $this->propagationStopped = $flag;
    }

    /**
     * @inheritDoc
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}