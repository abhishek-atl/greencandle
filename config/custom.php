<?php

return [

    'stripe_public_key' => env('STRIPE_PUBLIC_KEY'),
    'stripe_secret_key' => env('STRIPE_SECRET_KEY'),

    'text_local_api_key' => env('TEXTLOCAL_API_KEY'),
    'textlocal_sender' => env('TEXTLOCAL_SENDER'),
    'textlocal_sender_to_therapists' => env('TEXTLOCAL_SENDER_TO_THERAPISTS'),
    'textlocal_sender_to_clients' => env('TEXTLOCAL_SENDER_TO_CLIENTS'),

    'db' => [
        'per_page' => 25
    ],
    'download' => [
        'url' => env('DOWNLOAD_URL', '/storage/')
    ],
    'upload' => [
        'disk' => env('STORAGE_DISK', 'public_uploads'),
        'blog_path' => 'posts',
        'treatment_path' => 'treatments',
        'user_path' => 'users',
        'therapist_path' => 'therapists',
        'massage_locations_path' => 'massage-locations',
    ],
    'date' => [
        'show_format' => 'd M Y'
    ],
    'format' => [
        'date_short' => 'd M Y',
        'date_long' => 'd M Y',
        'time' => 'H:i',
        'date_time' => 'd-m-Y H:i',
    ]
];
