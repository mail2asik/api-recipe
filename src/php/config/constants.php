<?php

return [
    'domain_web' => "https://recipe.local:446",
    'domain_api' => "api.recipe.local",
    'domain_admin' => "admin.recipe.local",

    'roles'  => [
        'user'   => 'user'
    ],

    'user_statuses' => [
        'pending'       => 'pending',
        'approved'      => 'approved',
        'disapproved'   => 'disapproved',
        'suspended'     => 'suspended',
    ],

    'recipe_category'  => [
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
