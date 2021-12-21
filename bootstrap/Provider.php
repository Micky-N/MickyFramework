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
        \App\Events\UpdateCategoryEvent::class => [
            \App\Listeners\UpdateCategoryListener::class
        ]
    ]
];