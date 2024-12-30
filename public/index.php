<?php
require_once('../src/Model/twig.php');
require_once('../config/routes.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$data = [];

switch ($page) {
    case 'home':
        $data = require_once('../src/Controller/Accueil.php');
        $template = 'Accueil.twig';
        break;
    case 'entreprise':
        $data = require_once('../src/Controller/Entreprise.php');
        $template = 'Entreprise.twig';
        break;
    case 'inscription':
        $data = require_once('../src/Controller/Inscription.php');
        $template = 'Inscription.twig';
        break;
    case 'stagiaire':
        $data = require_once('../src/Controller/Stagiaire.php');
        $template = 'Stagiaire.twig';
        break;
    case 'aide':
        $data = require_once('../src/Controller/Aide.php');
        $template = 'Aide.twig';
        break;
    case 'deconnexion':
        $data = require_once('../src/Controller/Deconnexion.php');
        $template = 'Deconnexion.twig';
        break;
    default:
        // Page par défaut ou page d'erreur
        echo "Page non trouvée";
        exit;
}

// Ajouter les routes et la page actuelle aux données
$data['routes'] = $routes;
$data['current_page'] = $page;

// Afficher la page avec Twig
echo $twig->render($template, $data);
?>