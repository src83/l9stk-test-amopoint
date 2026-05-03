<?php

return [

    'default_route' => env('CABINET_DEFAULT_ROUTE', 'cabinet.index'), // 1st route after login

    'brand_route' => env('CABINET_BRAND_ROUTE', 'cabinet.index'), // route on logo

    'menu' => [
        'items_hidden' => [
            // 'home',
        ],
    ],

];
