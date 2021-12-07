<?php


namespace Tests\Core\Helpers\Permission;


use App\Models\User;
use Core\Interfaces\VoterInterface;

class AlwaysNoVoter implements VoterInterface
{

    public function canVote(string $permission, $subject = null): bool
    {
        return true;
    }

    public function vote(User $user, string $permission, $subject = null): bool
    {
        return false;
    }
}