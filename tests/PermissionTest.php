<?php

namespace Tests;


use App\Models\User;
use PHPUnit\Framework\TestCase;
use Tests\App\Permission\AlwaysNoVoter;
use Tests\App\Permission\AlwaysYesVoter;
use Tests\App\Permission\PermissionClass;
use Tests\App\Permission\SellerVoter;
use Tests\App\Permission\SpecificVoter;
use Tests\App\Permission\TestProduct;

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
        $user = new \stdClass();
        $user->id = 7;
        $this->assertFalse($this->permission->can($user, 'demo'));
    }

    public function testWithTrueVoter()
    {
        $this->permission->addVoter(new AlwaysYesVoter());
        $user = new \stdClass();
        $user->id = 7;
        $this->assertTrue($this->permission->can($user, 'demo'));
    }

    public function testWithOneVoterTrue()
    {
        $this->permission = new PermissionClass();
        $user = new \stdClass();
        $user->id = 7;
        $this->permission->addVoter(new AlwaysYesVoter());
        $this->permission->addVoter(new AlwaysNoVoter());
        $this->assertTrue($this->permission->can($user, 'demo'));
    }

    public function testWithSpecificVoter()
    {
        $this->permission = new PermissionClass();
        $user = new \stdClass();
        $user->id = 7;
        $this->permission->addVoter(new SpecificVoter());
        $this->assertFalse($this->permission->can($user, 'demo'));
        $this->assertTrue($this->permission->can($user, 'specific'));
    }

    public function testWithConditionVoter()
    {
        $this->permission = new PermissionClass();
        $user = new \stdClass();
        $user->id = 7;
        $user2 = new \stdClass();
        $user2->id = 1;
        $product = new TestProduct($user);
        $this->permission->addVoter(new SellerVoter());
        $this->assertTrue($this->permission->can($user, SellerVoter::EDIT, $product));
        $this->assertFalse($this->permission->can($user2, SellerVoter::EDIT, $product));
    }
}
