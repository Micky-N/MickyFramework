<?php


namespace Core;


use App\Models\User;
use Core\Interfaces\VoterInterface;

class ConsoleDebugger
{

    public static function permissionDebug(VoterInterface $voter, bool $vote, string $permission, User $user, $subject = null): void
    {
        $className = get_class($voter);
        $vote = $vote ? "\e[32myes\e[0m" : "\e[31mno\e[0m";
        file_put_contents('php://stdout',"\e[34m$className : \e[37m$vote on $permission\n");
    }
}