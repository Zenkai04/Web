<?php
require_once('../src/Model/connect.php');
require_once('../src/Model/twig.php');
require_once('../src/Model/close.php');
require_once('../config/routes.php');

// Déterminer quelle page afficher
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Récupérer les données depuis la base de données
$entreprises = $pdo->query('SELECT num_entreprise, raison_sociale, nom_contact, nom_resp, rue_entreprise, cp_entreprise, ville_entreprise, tel_entreprise, fax_entreprise, email, observation, site_entreprise FROM entreprise')->fetchAll();
$etudiants = $pdo->query('SELECT num_etudiant, nom_etudiant, prenom_etudiant FROM etudiant')->fetchAll();
$professeurs = $pdo->query('SELECT num_prof, nom_prof FROM professeur')->fetchAll();
$specialites = $pdo->query('SELECT num_spec, libelle FROM specialite')->fetchAll();
$spec_entreprises = $pdo->query('SELECT num_entreprise, num_spec FROM spec_entreprise')->fetchAll();
$classes = $pdo->query('SELECT num_classe, nom_classe FROM classe')->fetchAll();
$stages = $pdo->query('SELECT num_stage, debut_stage, fin_stage, type_stage, desc_projet, observation_stage, num_etudiant, num_prof, num_entreprise FROM stage')->fetchAll();
$prof_classes = $pdo->query('SELECT num_prof, num_classe FROM prof_classe')->fetchAll();


// Passer les données et les routes dans Twig
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises,
    'etudiants' => $etudiants,
    'professeurs' => $professeurs
];

// Afficher la page accueil
$template = isset($routes[$page]) ? $routes[$page] : $routes['home'];
echo $twig->render($template, $data);
?>