<?php


return [
    \App\Events\CategoryEvent::class => [
        'update' => \App\Listeners\UpdateCategoryListener::class,
    ],
];