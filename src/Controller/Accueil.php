<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/twig.php');
require_once(__DIR__ . '/../Model/close.php');
require_once(__DIR__ . '/../../config/routes.php');
require_once(__DIR__ . '/../Model/model.php'); 

// Passer les données et les routes dans Twig
$data = [
    'routes' => $routes,
];


?>