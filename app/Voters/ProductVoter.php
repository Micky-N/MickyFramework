<?php

namespace App\Voters;

use App\Models\User;
use Core\Interfaces\VoterInterface;
use RuntimeException;
use App\Models\Product;

class ProductVoter implements VoterInterface
{
    const EDIT = 'edit';

    public function canVote(string $permission, $subject = null): bool
    {
        return ($permission === self::EDIT) && ($subject instanceof Product);
    }

    public function vote(User $user, string $permission, $subject = null): bool
    {
        if(!$subject instanceof Product){
            throw new RuntimeException('le sujet doit être une instance de Product.');
        }

        return true;
    }
}