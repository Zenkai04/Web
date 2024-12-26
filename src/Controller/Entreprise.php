<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

// Récupérer les données depuis la base de données
$entreprises = getEntreprises1($pdo);

// Passer les données et les routes dans un tableau
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises
];

// Retourner les données
return $data;
?>