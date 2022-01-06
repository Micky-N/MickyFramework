<?php


namespace Tests\App\Permission;


use Core\Interfaces\VoterInterface;
use Exception;
use App\Models\User;

class PermissionClass
{
    /**
     * @var VoterInterface[]
     */
    private array $voters = [];

    /**
     * Autorise l'accès si true
     *
     * @param object $user
     * @param string $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function can($user, string $permission, $subject = null): bool
    {
        if($this->test($user, $permission, $subject) === false){
            return false;
        }
        return true;
    }

    /**
     * Ajoute un voter
     *
     * @param VoterInterface $voter
     */
    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }

    /**
     * Test la permission
     *
     * @param mixed $user
     * @param string $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function test($user, string $permission, $subject = null): bool
    {
        foreach ($this->voters as $voter) {
            if($voter->canVote($permission, $subject)){
                $vote = $voter->vote($user, $permission, $subject);
                if($vote === true){
                    return true;
                }
            }
        }
        return false;
    }
}