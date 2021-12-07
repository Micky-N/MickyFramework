<?php


namespace Core\Interfaces;


use App\Models\User;

interface VoterInterface
{
    /**
     * @param string $permission
     * @param null $subject
     * @return bool
     */
    public function canVote(string $permission, $subject = null): bool;

    /**
     * @param User $user
     * @param string $permission
     * @param null $subject
     * @return bool
     */
    public function vote(User $user, string $permission, $subject = null): bool;
}