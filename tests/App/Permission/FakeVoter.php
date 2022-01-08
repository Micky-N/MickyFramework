<?php


namespace Tests\App\Permission;


use Core\Exceptions\Voter\VoterException;

class FakeVoter implements \Core\Interfaces\VoterInterface
{

    /**
     * @inheritDoc
     */
    public function canVote(string $permission, $subject = null): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function vote($user, string $permission, $subject = null): bool
    {
        if(!$subject instanceof TestProduct){
            throw new VoterException('Subject must be an instance of '. TestProduct::class);
        }
        return true;
    }
}