<?php
require_once('../Model/connect.php'); // Connexion à la base de données
require_once('../Model/twig.php');   // Twig pour le rendu des templates

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entreprise = $_POST['entreprise'];
    $etudiant = $_POST['etudiant'];
    $professeur = $_POST['professeur'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $observations = $_POST['observations'];

    // Insérer les données dans la base de données
    $query = $pdo->prepare("
        INSERT INTO stages (entreprise, etudiant, professeur, date_debut, date_fin, type, description, observations)
        VALUES (:entreprise, :etudiant, :professeur, :date_debut, :date_fin, :type, :description, :observations)
    ");
    $query->execute([
        ':entreprise' => $entreprise,
        ':etudiant' => $etudiant,
        ':professeur' => $professeur,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':type' => $type,
        ':description' => $description,
        ':observations' => $observations,
    ]);

    echo $twig->render('confirmation.twig', ['message' => 'Inscription réussie !']);
}
?>
