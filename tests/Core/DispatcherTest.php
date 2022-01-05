<?php

namespace Tests\Core;

use Core\App;
use Core\Exceptions\Dispatcher\EventNotFoundException;
use Core\Exceptions\Dispatcher\EventNotImplementException;
use Core\Exceptions\Dispatcher\ListenerNotFoundException;
use Core\Exceptions\Dispatcher\ListenerNotImplementException;
use PHPUnit\Framework\TestCase;
use Tests\Core\App\Event\TestEvent;
use Tests\Core\App\Event\TestNotFoundEvent;
use Tests\Core\App\Event\TestNotImplementEvent;
use Tests\Core\App\Event\TestNoAliasListener;
use Tests\Core\App\Event\TestNotSubscribeListener;
use Tests\Core\App\Event\TodoTestClass;

class DispatcherTest extends TestCase
{
    /**
     * @var mixed
     */
    private $todoTest;

    public function setUp(): void
    {
        $this->todoTest = new TodoTestClass(1, 'eat burger', false, 'Micky');
        App::setEvents();
    }

    public function testConstructor()
    {
        $event = TestEvent::dispatch($this->todoTest);
        $this->assertInstanceOf(TestEvent::class, $event, "Teste la construction de l'event");
        $this->assertInstanceOf(TodoTestClass::class, $event->getTarget(), "Teste le type de la cible");
    }

    public function testEventWithAlias()
    {
        TestEvent::dispatch($this->todoTest, 'test');
        $this->assertTrue($this->todoTest->getCompleted(), "Teste l'ecoute de l'event avec alias");
    }

    public function testEventWithNoAlias()
    {
        TestEvent::dispatch($this->todoTest, TestNoAliasListener::class);
        $this->assertTrue($this->todoTest->getName() === 'burger eaten', "Teste l'ecoute de l'event sans alias");
    }

    public function testEventStopPropagation()
    {
        $event = TestEvent::dispatch($this->todoTest, ['propagation', 'test', TestNoAliasListener::class]);
        $this->assertTrue($event->isPropagationStopped(), "Teste l'arrêt de la propagation de l'event");
        $this->assertFalse($this->todoTest->getCompleted(), "Teste si la valeur a été modifié après l'arrêt de la propagation");
    }

    public function testEventNotImplementException()
    {
        // Teste l'implementation de l'EventInterface
        $this->expectException(EventNotImplementException::class);
        TestNotImplementEvent::dispatch($this->todoTest);
    }

    public function testEventNotFoundException()
    {
        // Teste la présence de l'event dans l'EventServiceProvider
        $this->expectException(EventNotFoundException::class);
        TestNotFoundEvent::dispatch($this->todoTest, ['test']);
    }

    public function testListenerNotFoundException()
    {
        // Teste la présence du listener dans l'EventServiceProvider
        $this->expectException(ListenerNotFoundException::class);
        TestEvent::dispatch($this->todoTest, 'notFound');
    }

    public function testListenerNotImplementException()
    {
        // Teste la présence du listener dans l'EventServiceProvider
        $this->expectException(ListenerNotImplementException::class);
        TestEvent::dispatch($this->todoTest, TestNotSubscribeListener::class);
    }
}
