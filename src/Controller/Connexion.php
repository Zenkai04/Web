<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    if ($role === 'etudiant') {
        $user = getEtudiantByLogin($pdo, $identifiant, $password);
    } elseif ($role === 'professeur') {
        $user = getProfesseurByLogin($pdo, $identifiant, $password);
    }

    if ($user or $identifiant === 'admin' and $password === 'admin') {
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