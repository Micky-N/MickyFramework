<?php


namespace Tests\App\Permission;


use Core\Interfaces\VoterInterface;
use RuntimeException;

class SellerVoter implements VoterInterface
{
    const EDIT = 'edit_product';

    public function canVote(string $permission, $subject = null): bool
    {
        return ($permission === self::EDIT) && ($subject instanceof TestProduct);
    }

    public function vote($user, string $permission, $subject = null): bool
    {
        if(!$subject instanceof TestProduct){
            throw new RuntimeException('Subject must be an instance of '. TestProduct::class);
        }

        return $subject->getSeller() === $user;
    }
}