<?php


namespace Core\Interfaces;


use App\Models\User;

interface VoterInterface
{
    /**
     * Verifie si le Voter peut voter
     *
     * @param string $permission
     * @param null $subject
     * @return bool
     */
    public function canVote(string $permission, $subject = null): bool;

    /**
     * Retourne le vote du Voter
     *
     * @param User $user
     * @param string $permission
     * @param null $subject
     * @return bool
     */
    public function vote(User $user, string $permission, $subject = null): bool;
}