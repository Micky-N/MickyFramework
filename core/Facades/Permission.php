<?php


namespace Core\Facades;

use Core\Interfaces\VoterInterface;
use Core\Permission as CorePermission;

/**
 * @method static \Core\Permission can(string $permission, $subject = null)
 * @method static \Core\Permission authorize($user, string $permission, $subject = null)
 * @method static \Core\Permission authorizeAuth(string $permission, $subject = null)
 * @method static \Core\Permission addVoter(VoterInterface $voter)
 *
 * @see \Core\Permission
 */
class Permission
{
    /**
     * @var CorePermission|null
     */
    public static ?CorePermission $permission;

    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$permission)){
            self::$permission = new CorePermission();
        }
        return call_user_func_array([self::$permission, $method], $arguments);
    }
}