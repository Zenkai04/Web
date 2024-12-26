<?php
require_once('../Model/connect.php');
require_once('../Model/twig.php');
require_once('../Model/close.php');
require_once('../config/routes.php');
require_once('../Model/model.php'); 

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