<?php


namespace Core\Interfaces;


use App\Models\User;

interface VoterInterface
{
    public function canVote(string $permission, $subject = null): bool;
    public function vote(User $user, string $permission, $subject = null): bool;
}