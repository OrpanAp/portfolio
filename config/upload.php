<?php

declare(strict_types=1);

return [
    'max_file_size' => 50 * 1024 * 1024,

    'projects_path' =>
    __DIR__ . '/../public/projects',

    'cv_path' =>
    __DIR__ . '/../public/uploads/cv',

    'thumbnail_path' =>
    __DIR__ . '/../public/uploads/thumbnails',

    'allowed_extensions' => [
        'zip',
    ],

    'cv_allowed_extensions' => [
        'pdf',
    ],

    'thumbnail_allowed_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'webp',
    ],

    'cv_max_file_size' => 10 * 1024 * 1024,

    'thumbnail_max_file_size' => 5 * 1024 * 1024,
];
