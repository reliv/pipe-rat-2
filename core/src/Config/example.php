<?php
/**
 * example.php
 */
return [
    'routes' => [
        RouteFind::get(
            'rcm-site',
            [
                'doctrine-entity' => Entity::class
            ]
        ),

        RouteFindById::get(
            'rcm-site',
            [
                'doctrine-entity' => Entity::class
            ]
        )
    ],
];
