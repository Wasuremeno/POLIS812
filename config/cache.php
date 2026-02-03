<?php
return [
    'driver' => 'file',
    'file' => [
        'path' => __DIR__ . '/../storage/cashe',
        'prefix' => 'cache_'
    ],
    'memory' => [
        'prefix' => 'cache_'
    ],
];