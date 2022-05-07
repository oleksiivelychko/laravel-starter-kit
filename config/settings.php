<?php

return [
    'schema' => [
        'string_length' => 191,
        'pagination_limit' => 10
    ],

    'assets' => [
        'not-found' => 'images/not-found.jpeg'
    ],

    'import' => [
        'products_json' => '/app/storage/app/public/import/products.json'
    ],

    'languages' => [
        'English'       => 'en',
        'Українська'    => 'uk',
    ],

    'intl' => [
        'en' => [
            'en_US' => 'America/New_York',
        ],
        'uk' => [
            'uk_UA' => 'Europe/Kiev',
        ],
    ]
];
