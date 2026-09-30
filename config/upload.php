<?php

declare(strict_types=1);

return [
    'max_file_size' => 50 * 1024 * 1024,

    'projects_path' =>
    __DIR__ . '/../public/projects',

    'cv_path' =>
    __DIR__ . '/../public/uploads/cv',

    'allowed_extensions' => [
        'zip',
    ],

    'cv_allowed_extensions' => [
        'pdf',
    ],

    'cv_max_file_size' => 10 * 1024 * 1024,
];
