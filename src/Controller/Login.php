<?php
session_start();

// Connexion à la base de données
$host = 'localhost';  // Remplace par ton hôte
$dbname = 'ma_base';  // Remplace par le nom de ta base de données
$username = 'root';   // Remplace par ton nom d'utilisateur DB
$password = '';       // Remplace par ton mot de passe DB

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['password'];

    // Préparer la requête pour vérifier l'utilisateur
    $stmt = $pdo->prepare('SELECT id, identifiant, mdp, role FROM users WHERE identifiant = :identifiant');
    $stmt->execute(['identifiant' => $identifiant]);
    $user = $stmt->fetch();

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($mdp, $user['mdp'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: /dashboard.php');  // Rediriger vers la page d'accueil ou tableau de bord
        exit;
    } else {
        // Mot de passe ou identifiant incorrect
        $error = "Identifiants incorrects";
    }
}
?>