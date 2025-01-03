<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$data = [
    'routes' => $routes,
    'current_page' => 'aide',
];

return $data;
?>