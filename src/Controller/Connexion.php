<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    // Vérifier si l'utilisateur est un étudiant ou un professeur
    $etudiant = getEtudiantByLogin($pdo, $identifiant, $password);
    $professeur = getProfesseurByLogin($pdo, $identifiant, $password);

    if ($etudiant) {
        $_SESSION['user'] = $etudiant;
        $_SESSION['role'] = 'etudiant';
        header('Location: ?page=home');
        exit;
    } elseif ($professeur) {
        $_SESSION['user'] = $professeur;
        $_SESSION['role'] = 'professeur';
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