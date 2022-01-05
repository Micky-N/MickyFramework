<?php


namespace Tests\Core\App\Event;


class TodoTestClass
{
    private $id;
    private $name;
    private $completed;
    private $user;

    public function __construct($id, $name, $completed, $user)
    {
        $this->id = $id;
        $this->name = $name;
        $this->completed = $completed;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return TodoTestClass
     */
    public function setCompleted(bool $completed): TodoTestClass
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @param mixed $name
     * @return TodoTestClass
     */
    public function setName($name): TodoTestClass
    {
        $this->name = $name;
        return $this;
    }
}