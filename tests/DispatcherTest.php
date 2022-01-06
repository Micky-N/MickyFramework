<?php

namespace Tests;

use Core\App;
use Core\Exceptions\Dispatcher\EventNotFoundException;
use Core\Exceptions\Dispatcher\EventNotImplementException;
use Core\Exceptions\Dispatcher\ListenerNotFoundException;
use Core\Exceptions\Dispatcher\ListenerNotImplementException;
use PHPUnit\Framework\TestCase;
use Tests\App\Event\TestEvent;
use Tests\App\Event\TestNotFoundEvent;
use Tests\App\Event\TestNotImplementEvent;
use Tests\App\Event\TestNoAliasListener;
use Tests\App\Event\TestNotSubscribeListener;
use Tests\App\Event\TodoTestClass;

class DispatcherTest extends TestCase
{
    /**
     * @var mixed
     */
    private $todoTest;

    public function setUp(): void
    {
        $this->todoTest = new TodoTestClass(1, 'eat burger', false, 'Micky');
        App::setEvents(\Tests\App\Event\TestEvent::class, 'test', \Tests\App\Event\TestAliasListener::class);
        App::setEvents(\Tests\App\Event\TestEvent::class, 'propagation', \Tests\App\Event\TestPropagationListener::class);
    }

    public function testConstructor()
    {
        $event = TestEvent::dispatch($this->todoTest);
        $this->assertInstanceOf(TestEvent::class, $event);
        $this->assertInstanceOf(TodoTestClass::class, $event->getTarget());
    }

    public function testEventWithAlias()
    {
        TestEvent::dispatch($this->todoTest, 'test');
        $this->assertTrue($this->todoTest->getCompleted());
    }

    public function testEventWithNoAlias()
    {
        TestEvent::dispatch($this->todoTest, TestNoAliasListener::class);
        $this->assertTrue($this->todoTest->getName() === 'burger eaten');
    }

    public function testEventStopPropagation()
    {
        $event = TestEvent::dispatch($this->todoTest, ['propagation', 'test', TestNoAliasListener::class]);
        $this->assertTrue($event->isPropagationStopped());
        $this->assertFalse($this->todoTest->getCompleted());
    }

    public function testEventNotImplementException()
    {
        $this->expectException(EventNotImplementException::class);
        TestNotImplementEvent::dispatch($this->todoTest);
    }

    public function testEventNotFoundException()
    {
        $this->expectException(EventNotFoundException::class);
        TestNotFoundEvent::dispatch($this->todoTest, ['test']);
    }

    public function testListenerNotFoundException()
    {
        $this->expectException(ListenerNotFoundException::class);
        TestEvent::dispatch($this->todoTest, 'notFound');
    }

    public function testListenerNotImplementException()
    {
        $this->expectException(ListenerNotImplementException::class);
        TestEvent::dispatch($this->todoTest, TestNotSubscribeListener::class);
    }
}
