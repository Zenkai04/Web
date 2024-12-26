<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Model/connect.php');
require_once('../Model/twig.php');
require_once('../Model/model.php'); 

header('Content-Type: application/json');

try {
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
        insertInscription($pdo, $entreprise, $etudiant, $professeur, $date_debut, $date_fin, $type, $description, $observations);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>