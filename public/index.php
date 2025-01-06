<?php
session_start();
require_once('../src/Model/twig.php');
require_once('../config/routes.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (!isset($_SESSION['user']) && $page !== 'connexion') {
    header('Location: ?page=connexion');
    exit;
}

$data = [];

// Restrict access based on user role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant' && !in_array($page, ['home', 'entreprise', 'aide', 'stagiaire', 'deconnexion'])) {
    $_SESSION['error_message'] = "Vous n'avez pas les droits nécessaires pour accéder à cette page.";
    header('Location: ?page=home');
    exit;
}

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
    case 'connexion':
        $data = require_once('../src/Controller/Connexion.php');
        $template = 'Connexion.twig';
        break;
    case 'deconnexion':
        session_destroy();
        header('Location: ?page=connexion');
        exit;
    case 'editEtu':
        $data = require_once('../src/Controller/EditEtu.php');
        $template = 'EditEtu.twig';
        break;
    case 'editEnt':
        $data = require_once('../src/Controller/EditEnt.php');
        $template = 'EditEnt.twig';
        break;
    case 'showEtu':
        $data = require_once('../src/Controller/ShowEtu.php');
        $template = 'ShowEtu.twig';
        break;
    case 'showEnt':
        $data = require_once('../src/Controller/ShowEnt.php');
        $template = 'ShowEnt.twig';
        break;
    default:
        $data = require_once('../src/Controller/Accueil.php');
        $template = 'Accueil.twig';
        break;
}

if (isset($_SESSION['error_message'])) {
    $data['error_message'] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

echo $twig->render($template, $data);
?>