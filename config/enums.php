<?php

return [
    'test' => 'test',
    'general' => [
        'boolean' => [
            1 => 'yes',
            0 => 'no',
        ],
        'check' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'publish' => [
            'publish' => 'publish',
            'draft' => 'draft',
        ],
        'gender' => [
            'male' => 'male',
            'female' => 'female',
        ],
    ],

    'user' => [
        'status' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'roles' => [
            1 => 'admin',
            2 => 'editor',
            3 => 'member',
        ],
        'permissions' => [],
    ],

    'beacon' => [
        'local' => [
            'inside' => 'inside',
            'outside' => 'outside',
        ],
    ],

    'module' => [
        'type' => [
            'main' => 'main',
            'slide' => 'slide',
        ],
        'audio_type' => [
            '' => 'default',
            'ar' => 'spatial',
        ],
        'video_type' => [
            '' => 'default',
            'ar' => 'augmented_reality',
            '360' => '360_video',
        ],
    ],
];
