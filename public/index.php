<?php
require_once('../src/Model/connect.php');
require_once('../src/Model/twig.php');
require_once('../src/Model/close.php');
require_once('../config/routes.php'); // Inclusion des routes

// Déterminer quelle page afficher
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Récupérer les données depuis la base de données
$entreprises = $pdo->query('SELECT num_entreprise, raison_sociale FROM entreprise')->fetchAll();
$etudiants = $pdo->query('SELECT num_etudiant, nom_etudiant, prenom_etudiant FROM etudiant')->fetchAll();
$professeurs = $pdo->query('SELECT num_prof, nom_prof FROM professeur')->fetchAll();

// Passer les données et les routes dans Twig
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises,
    'etudiants' => $etudiants,
    'professeurs' => $professeurs
];

// Afficher la page appropriée
$template = isset($routes[$page]) ? $routes[$page] : $routes['home'];
echo $twig->render($template, $data);
?>