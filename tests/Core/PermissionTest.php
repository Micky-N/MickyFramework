<?php

namespace Tests\Core;

use App\Models\User;
use Core\ConsoleDebugger;
use Core\Interfaces\PermissionDebuggerInterface;
use Core\Permission;
use PHPUnit\Framework\TestCase;
use Tests\Core\Helpers\Permission\AlwaysNoVoter;
use Tests\Core\Helpers\Permission\AlwaysYesVoter;
use Tests\Core\Helpers\Permission\SellerVoter;
use Tests\Core\Helpers\Permission\SpecificVoter;
use Tests\Core\Helpers\Permission\TestProduct;

class PermissionTest extends TestCase
{
    public function testEmptyVoters()
    {
        $permission = new Permission();
        $user = new User();
        $this->assertFalse($permission->can($user, 'demo'));
    }

    public function testWithTrueVoter()
    {
        $permission = new Permission();
        $permission->addVoter(new AlwaysYesVoter());
        $user = new User();
        $this->assertTrue($permission->can($user, 'demo'));
    }

    public function testWithOneVoterTrue()
    {
        $permission = new Permission();
        $user = new User();
        $permission->addVoter(new AlwaysYesVoter());
        $permission->addVoter(new AlwaysNoVoter());
        $this->assertTrue($permission->can($user, 'demo'));
    }

    public function testWithSpecificVoter()
    {
        $permission = new Permission();
        $user = new User();
        $permission->addVoter(new SpecificVoter());
        $this->assertFalse($permission->can($user, 'demo'));
        $this->assertTrue($permission->can($user, 'specific'));
    }

    public function testWithConditionVoter()
    {
        $permission = new Permission();
        $user = new User();
        $user2 = new User();
        $product = new TestProduct($user);
        $permission->addVoter(new SellerVoter());
        $this->assertTrue($permission->can($user, SellerVoter::EDIT, $product));
        $this->assertFalse($permission->can($user2, SellerVoter::EDIT, $product));
    }

    public function testDebug(){
        $permission = new Permission(new ConsoleDebugger());
        $user = new User();
        $permission->addVoter(new AlwaysNoVoter());
        $permission->addVoter(new AlwaysNoVoter());
        $permission->addVoter(new AlwaysNoVoter());
        $permission->addVoter(new AlwaysNoVoter());
        $permission->addVoter(new AlwaysYesVoter());
        $permission->addVoter(new AlwaysNoVoter());
        $this->assertTrue($permission->can($user, 'edit'));
    }
}
