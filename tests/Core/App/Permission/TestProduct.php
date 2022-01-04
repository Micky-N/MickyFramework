<?php


namespace Tests\Core\App\Permission;



use App\Models\User;

class TestProduct
{

    /**
     * @var User
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getSeller()
    {
        return $this->user;
    }

}