<?php

namespace App\UserModule\Managers;

use App\UserModule\Entities\User;
use Exception;
use MkyCore\Abstracts\Entity;
use MkyCore\Abstracts\Manager;
use MkyCore\Crypt;
use MkyCore\Interfaces\AuthSystemInterface;

/**
 * @Table('users')
 * @Entity('App\UserModule\Entities\User')
 */
class UserManager extends Manager implements AuthSystemInterface
{

    /**
     * @param Entity $entity
     * @param string $table
     * @return bool|Entity
     * @throws Exception
     */
    public function save(Entity $entity, string $table = ''): false|Entity
    {
        /** @var User $entity */
        $password = $entity->password();

        $entity->setPassword($password);
        return parent::save($entity, $table);
    }

    public function passwordCheck(array $credentials): Entity|false
    {
        $email = $credentials['email'];
        $password = $credentials['password'];
        if(!($user = $this->where('email', $email)->first())){
            return false;
        }

        if(!(Crypt::verify($password, $user->password()))){
            return false;
        }
        return $user;
    }
}