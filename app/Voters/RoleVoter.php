<?php


namespace App\Voters;


use App\Models\Role;
use App\Models\User;
use Core\Interfaces\VoterInterface;

class RoleVoter implements VoterInterface
{
    const SELLER = 'seller';
    const ADMIN = 'admin';

    /**
     * @inheritDoc
     */
    public function canVote(string $permission, $subject = null): bool
    {
        $constants = (new \ReflectionClass($this))->getConstants();
        return !empty($constants) && in_array($permission, $constants);
    }

    /**
     * @inheritDoc
     */
    public function vote($user, string $permission, $subject = null): bool
    {
        switch ($permission):
            case self::SELLER :
                return $this->canSeller($user);
            case self::ADMIN :
                return $this->canAdmin($user);
        endswitch;

        throw new \Exception('Le vote ne peut pas être validé');
    }

    private function canSeller($user)
    {
        return $user->role_id != Role::where('name', 'BUYER')->first()->id;
    }

    private function canAdmin($user)
    {
        return $user->role_id == Role::where('name', 'ADMIN')->first()->id;
    }
}