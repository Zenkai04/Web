<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$data = [
    'routes' => [
        'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        'css' => '/path/to/your/css',
        'images' => '/path/to/your/images',
        'js' => '/path/to/your/js',
    ],
    'current_page' => 'aide',
];

return $data;
?>