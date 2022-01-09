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
    /**
     * @var UserNotify
     */
    private UserNotify $user;

    public function setUp(): void
    {
        $this->user = new UserNotify(1, 'micky');
        App::setAlias('test', \Tests\App\Notification\NotificationSystem::class);
        App::setAlias('notSend', \Tests\App\Notification\NotificationNotSendSystem::class);
    }

    public function testActionNotification()
    {
        $process = [
            'default' => ['test' => 'default'],
            'action' => ['test' => 'action'],
        ];
        // No Action
        $this->user->notify(new NotificationTest($process));
        $this->assertEquals($process['default'], ReturnNotificationClass::getReturn());

        // With Action
        $this->user->notify(new NotificationTest($process, 'action'));
        $this->assertEquals($process['action'], ReturnNotificationClass::getReturn());
    }

    public function testNotInstantiableNotification()
    {
        try {
            $this->user->notify(new NotificationNotInstantiableTest());
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationException::class, $ex);
        }
    }

    public function testNotViaNotification()
    {
        try {
            $this->user->notify(new NotificationNotViaTest(['test' => true]));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationNotViaException::class, $ex);
        }
    }

    public function testNotAliasNotification()
    {
        try {
            $this->user->notify(new NotificationNotAliasTest(['test' => true]));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationNotAliasException::class, $ex);
        }
    }

    public function testNotToMethodNotification()
    {
        try {
            $this->user->notify(new NotificationNotToMethodTest(['test' => true]));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationException::class, $ex);
        }
    }

    public function testNotMessageNotification()
    {
        try {
            $this->user->notify(new NotificationNotMessageTest(['test' => true]));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationNotMessageException::class, $ex);
        }

    }

    public function testNotSendNotification()
    {
        try {
            $this->user->notify(new NotificationNotSendTest(['test' => true]));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(NotificationSystemException::class, $ex);
        }

    }
}
