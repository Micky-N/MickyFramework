<?php


namespace Tests\App\Permission;



class SpecificVoter implements \Core\Interfaces\VoterInterface
{

    public function canVote(string $permission, $subject = null): bool
    {
        return $permission === 'specific';
    }

    public function vote($user, string $permission, $subject = null): bool
    {
        return true;
    }
}