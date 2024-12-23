<?php
require_once('../src/connect.php');
require_once('../src/twig.php');
require_once('../src/close.php');
require_once('../config/routes.php'); // Inclusion des routes

// Passer les routes dans Twig
echo $twig->render('Accueil.twig', ['routes' => $routes]);
?>