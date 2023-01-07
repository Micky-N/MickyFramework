<?php

namespace App\UserModule\Entities;

use MkyCore\Abstracts\Entity;
use MkyCore\Traits\HasDbNotify;
use MkyCore\Traits\HasRememberToken;

/**
 * @Manager('App\UserModule\Managers\UserManager')
 */
class User extends Entity
{

    use HasRememberToken;
    use HasDbNotify;
    /**
    * @PrimaryKey
    */
    private $id;
    private $name;
	private $email;
	private $password;
    private $createdAt;
    private $updatedAt;

    public function id()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

	public function email()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }

	public function password()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function updatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}