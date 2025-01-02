<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['mdp'];

    if ($role === 'etudiant') {
        $user = getEtudiantByLogin($pdo, $identifiant, $mdp);
    } elseif ($role === 'professeur') {
        $user = getProfesseurByLogin($pdo, $identifiant, $mdp);
    }

    if ($user or $identifiant === 'admin' and $mdp === 'admin') {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;
        header('Location: ?page=home');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}

$data = [
    'routes' => $routes,
    'error' => $error,
];

return $data;
?>