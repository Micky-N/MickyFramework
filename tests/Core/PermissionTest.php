<?php

namespace Tests\Core;


use App\Models\User;
use PHPUnit\Framework\TestCase;
use Tests\Core\App\Permission\AlwaysNoVoter;
use Tests\Core\App\Permission\AlwaysYesVoter;
use Tests\Core\App\Permission\PermissionClass;
use Tests\Core\App\Permission\SellerVoter;
use Tests\Core\App\Permission\SpecificVoter;
use Tests\Core\App\Permission\TestProduct;

class PermissionTest extends TestCase
{

    /**
     * @var PermissionClass
     */
    private PermissionClass $permission;

    public function setUp(): void
    {
        $this->permission = new PermissionClass();
    }

    public function testEmptyVoters()
    {
        $user = User::find(7);
        $this->assertFalse($this->permission->can($user, 'demo'));
    }

    public function testWithTrueVoter()
    {
        $this->permission->addVoter(new AlwaysYesVoter());
        $user = new User();
        $this->assertTrue($this->permission->can($user, 'demo'));
    }

    public function testWithOneVoterTrue()
    {
        $this->permission = new PermissionClass();
        $user = User::find(7);
        $this->permission->addVoter(new AlwaysYesVoter());
        $this->permission->addVoter(new AlwaysNoVoter());
        $this->assertTrue($this->permission->can($user, 'demo'));
    }

    public function testWithSpecificVoter()
    {
        $this->permission = new PermissionClass();
        $user = User::find(7);
        $this->permission->addVoter(new SpecificVoter());
        $this->assertFalse($this->permission->can($user, 'demo'));
        $this->assertTrue($this->permission->can($user, 'specific'));
    }

    public function testWithConditionVoter()
    {
        $this->permission = new PermissionClass();
        $user = User::find(7);
        $user2 = User::find(2);
        $product = new TestProduct($user);
        $this->permission->addVoter(new SellerVoter());
        $this->assertTrue($this->permission->can($user, SellerVoter::EDIT, $product));
        $this->assertFalse($this->permission->can($user2, SellerVoter::EDIT, $product));
    }
}
