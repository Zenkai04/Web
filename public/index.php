<?php
require_once('../src/Model/connect.php');
require_once('../src/Model/twig.php');
require_once('../src/Model/close.php');
require_once('../config/routes.php'); // Inclusion des routes

// Passer les routes dans Twig
echo $twig->render('Accueil.twig', ['routes' => $routes]);
?>