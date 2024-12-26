<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/twig.php');
require_once(__DIR__ . '/../Model/close.php');
require_once(__DIR__ . '/../../config/routes.php');
require_once(__DIR__ . '/../Model/model.php'); 

// Récupérer les données depuis la base de données
$entreprises = getEntreprises($pdo);

// Passer les données et les routes dans Twig
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises
];

// Afficher la page Entreprise
echo $twig->render('Entreprise.twig', $data);
?>