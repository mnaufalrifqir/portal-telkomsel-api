<?php

return [
    'disks' => [
        'portal' => [
            'driver' => 'local',
            'root' => 'images',
            'visibility' => 'public',
        ],
        'user-guide' => [
            'driver' => 'local',
            'root' => 'pdf',
            'visibility' => 'public',
        ],
    ],
];