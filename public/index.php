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
    default:
        // Page par défaut ou page d'erreur
        echo "Page non trouvée";
        exit;
}

// Afficher la page avec Twig
echo $twig->render($template, $data);
?>