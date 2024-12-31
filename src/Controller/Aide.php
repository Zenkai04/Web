<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

// Passer les données et les routes dans un tableau
$data = [
    'aide' => $routes,
];

// Retourner les données
return $data;
?>