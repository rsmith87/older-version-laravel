<?php
return [
    'user' => [
        'table' => 'users',
        'model' => 'App\User',
        'columns' => ['id', 'name']
    ],
    'broadcast' => [
      'enable' => false,
        'app_name' => 'legalkeeper_chat',
        'pusher' => [
            'app_id'        => env('PUSHER_APP_ID'),
            'app_key'       => env('PUSHER_APP_KEY'),
            'app_secret'    => env('PUSHER_APP_SECRET'),
            'options' => [
                 'cluster' => env('PUSHER_CLUSTER'),
                 'encrypted' => true
            ]
        ]
    ]
];
