<?php

namespace Tests;


use Core\Exceptions\Voter\VoterException;
use Core\Permission;
use PHPUnit\Framework\TestCase;
use Tests\App\Permission\AlwaysNoVoter;
use Tests\App\Permission\AlwaysYesVoter;
use Tests\App\Permission\FakeVoter;
use Tests\App\Permission\SellerVoter;
use Tests\App\Permission\SpecificVoter;
use Tests\App\Permission\TestProduct;

class PermissionTest extends TestCase
{

    /**
     * @var Permission
     */
    private Permission $permission;

    public function setUp(): void
    {
        $this->permission = new Permission();
    }

    public function testEmptyVoters()
    {
        $user = new \stdClass();
        $user->id = 7;
        $this->assertFalse($this->permission->test($user, 'demo'));
    }

    public function testWithTrueVoter()
    {
        $this->permission->addVoter(new AlwaysYesVoter());
        $user = new \stdClass();
        $user->id = 7;
        $this->assertTrue($this->permission->test($user, 'demo'));
    }

    public function testWithFalseVoter()
    {
        $this->permission->addVoter(new AlwaysNoVoter());
        $user = new \stdClass();
        $user->id = 7;
        $this->assertFalse($this->permission->test($user, 'demo'));
    }

    public function testWithOneVoterTrue()
    {
        $user = new \stdClass();
        $user->id = 7;
        $this->permission->addVoter(new AlwaysYesVoter());
        $this->permission->addVoter(new AlwaysNoVoter());
        $this->assertTrue($this->permission->test($user, 'demo'));
    }

    public function testWithSpecificVoter()
    {
        $user = new \stdClass();
        $user->id = 7;
        $this->permission->addVoter(new SpecificVoter());
        $this->assertFalse($this->permission->test($user, 'demo'));
        $this->assertTrue($this->permission->test($user, 'specific'));
    }

    public function testWithConditionVoter()
    {
        $user = new \stdClass();
        $user->id = 7;
        $user2 = new \stdClass();
        $user2->id = 1;
        $product = new TestProduct($user);
        $this->permission->addVoter(new SellerVoter());
        $this->assertTrue($this->permission->test($user, SellerVoter::EDIT, $product));
        $this->assertFalse($this->permission->test($user2, SellerVoter::EDIT, $product));
        try {
            $this->permission->addVoter(new FakeVoter());
            $this->assertTrue($this->permission->test($user, true, $product));
            $this->permission->test($user, true, $user2);
        }catch (VoterException $ex){
            $this->assertInstanceOf(VoterException::class, $ex);
        }
    }
}
