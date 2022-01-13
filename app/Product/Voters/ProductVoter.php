<?php

namespace App\Product\Voters;

use App\Product\Models\Product;
use Core\Exceptions\Voter\VoterException;
use Core\Interfaces\VoterInterface;

class ProductVoter implements VoterInterface
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function canVote(string $permission, $product = null): bool
    {
        $constants = (new \ReflectionClass($this))->getConstants();
        return !empty($constants) && in_array($permission, $constants) && ($product instanceof Product);
    }

    public function vote($user, string $permission, $product = null): bool
    {
        switch ($permission) {
            case self::EDIT:
                return $this->canEdit($user, $product);
            case self::DELETE:
                return $this->canDelete($user, $product);
                break;
        }

        throw new \Exception('Not valid vote');
    }

    private function canEdit($user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    private function canDelete($user, Product $product)
    {
        return $user->id === $product->user_id;
    }
}