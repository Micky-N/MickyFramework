<?php


namespace Tests\Core\Helpers\Permission;


use App\Models\User;

class SpecificVoter implements \Core\Interfaces\VoterInterface
{

    public function canVote(string $permission, $subject = null): bool
    {
        return $permission === 'specific';
    }

    public function vote(User $user, string $permission, $subject = null): bool
    {
        return true;
    }
}