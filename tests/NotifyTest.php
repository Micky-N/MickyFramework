<?php

namespace Tests;

use Core\App;
use Core\Exceptions\Notification\NotificationException;
use Core\Exceptions\Notification\NotificationNotAliasException;
use Core\Exceptions\Notification\NotificationNotMessageException;
use Core\Exceptions\Notification\NotificationNotViaException;
use Core\Exceptions\Notification\NotificationSystemException;
use PHPUnit\Framework\TestCase;
use Tests\App\Notification\NotificationNotAliasTest;
use Tests\App\Notification\NotificationNotInstantiableTest;
use Tests\App\Notification\NotificationNotMessageTest;
use Tests\App\Notification\NotificationNotSendTest;
use Tests\App\Notification\NotificationNotToMethodTest;
use Tests\App\Notification\NotificationNotViaTest;
use Tests\App\Notification\NotificationTest;
use Tests\App\Notification\ReturnNotificationClass;
use Tests\App\Notification\UserNotify;

class NotifyTest extends TestCase
{
    public function setUp(): void
    {
        App::setAlias('test', \Tests\App\Notification\NotificationSystem::class);
        App::setAlias('notSend', \Tests\App\Notification\NotificationNotSendSystem::class);
    }

    public function testActionNotification()
    {
        $user = new UserNotify(1, 'micky');
        $process = [
            'default' => ['test'=> 'default'],
            'action' => ['test'=> 'action'],
        ];
        // No Action
        $user->notify(new NotificationTest($process));
        $this->assertEquals($process['default'], ReturnNotificationClass::getReturn());

        // With Action
        $user->notify(new NotificationTest($process, 'action'));
        $this->assertEquals($process['action'], ReturnNotificationClass::getReturn());
    }

    public function testNotInstantiableNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationException::class);
        $user->notify(new NotificationNotInstantiableTest());
    }

    public function testNotViaNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationNotViaException::class);
        $user->notify(new NotificationNotViaTest(['test'=> true]));
    }

    public function testNotAliasNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationNotAliasException::class);
        $user->notify(new NotificationNotAliasTest(['test'=> true]));
    }

    public function testNotToMethodNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationException::class);
        $user->notify(new NotificationNotToMethodTest(['test'=> true]));
    }

    public function testNotMessageNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationNotMessageException::class);
        $user->notify(new NotificationNotMessageTest(['test'=> true]));
    }

    public function testNotSendNotification()
    {
        $user = new UserNotify(1, 'micky');
        $this->expectException(NotificationSystemException::class);
        $user->notify(new NotificationNotSendTest(['test'=> true]));
    }
}
