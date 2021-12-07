<?php

namespace App\Voters;

use App\Models\User;
use Core\Interfaces\VoterInterface;
use RuntimeException;
use App\Models\Product;

class ProductVoter implements VoterInterface
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function canVote(string $permission, $product = null): bool
    {
        $constants = (new \ReflectionClass($this))->getConstants();
        return !empty($constants) && in_array($permission, $constants) && ($product instanceof Product);
    }

    public function vote(User $user, string $permission, $product = null): bool
    {
        if(!$product instanceof Product){
            throw new RuntimeException('le sujet doit être une instance de Product.');
        }
        switch ($permission) {
            case self::EDIT:
                return $this->canEdit($user, $product);
            case self::DELETE:
                return $this->canDelete($user, $product);
                break;
        }

        throw new \Exception('Le vote ne peut pas être validé');
    }

    private function canEdit(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }

    private function canDelete(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }
}