<?php

return [
    'domain_web' => "https://recipe.local:446",
    'domain_api' => "api.recipe.local",
    'domain_admin' => "admin.recipe.local",

    'roles'  => [
        "user"   => 'user'
    ],

    'user_statuses' => [
        'pending'       => 'pending',
        'approved'      => 'approved',
        'disapproved'   => 'disapproved',
        'suspended'     => 'suspended',
    ],

    'static_otp' => env('STATIC_OTP', false),

    'device_types' => [
        'android' => 'android',
        'ios' => 'ios'
    ]

];
