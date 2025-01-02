<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$data = [
    'routes' => [
        'bootstrap' => '../node_modules/bootstrap/dist/css/bootstrap.min.css',
        'css' => '../public/css',
        'images' => '../public/images',
        'js' => '../public/js',
    ],
    'current_page' => 'aide',
];

return $data;
?>