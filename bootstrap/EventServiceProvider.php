<?php


return [
    \App\Events\CategoryEvent::class => [
        'update' => \App\Listeners\UpdateCategoryListener::class,
    ],
    \Tests\Core\App\Event\TestEvent::class => [
        'test' => \Tests\Core\App\Event\TestAliasListener::class,
        'propagation' => \Tests\Core\App\Event\TestPropagationListener::class,
    ]
];