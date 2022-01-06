<?php


namespace Tests\App\Permission;


use Core\Interfaces\VoterInterface;

class AlwaysYesVoter implements VoterInterface
{

    public function canVote(string $permission, $subject = null): bool
    {
        return true;
    }

    public function vote($user, string $permission, $subject = null): bool
    {
        return true;
    }
}