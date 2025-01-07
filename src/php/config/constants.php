<?php

return [
    'domain_web' => env('DOMAIN_WEB', 'https://recipe.local:446'),
    'domain_api' => env('DOMAIN_API', 'api.recipe.local'),
    'domain_admin' => env('DOMAIN_ADMIN', 'admin.recipe.local'),

    'roles'  => [
        'admin'  => 'admin',
        'user'   => 'user'
    ],

    'user_statuses' => [
        'pending'       => 'pending',
        'approved'      => 'approved',
        'disapproved'   => 'disapproved',
        'suspended'     => 'suspended',
    ],

    'recipe_categories'  => [
        'veg'   => 'veg',
        'non_veg'   => 'non_veg'
    ],

    'recipe_statuses' => [
        'pending'       => 'pending',
        'approved'      => 'approved',
        'rejected'      => 'rejected',
    ],

    's3_default_recipe_image_name' => 'default.jpg', 

    's3_dir' => [
        'original' => 'common/images/original',
        'thumb' => 'common/images/thumb'
    ]

];
