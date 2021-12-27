<?php


return [
    'middlewares' => [
        'auth' => \App\Http\Middlewares\AuthMiddleware::class
    ],
    'voters' => [
	    \App\Voters\ProductVoter::class,
	    \App\Voters\RoleVoter::class,
    ],
    'events' => [
        \App\Events\CategoryEvent::class => [
            'update' => \App\Listeners\UpdateCategoryListener::class,
            'test' => \App\Listeners\TestCategoryListener::class
        ]
    ],
    'alias' => [
        'notification' => \Core\WebPushNotification::class
    ]
];