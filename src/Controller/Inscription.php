<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/twig.php');
require_once(__DIR__ . '/../Model/close.php');
require_once(__DIR__ . '/../../config/routes.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $entreprise1 = $_POST['entreprise'];
        $etudiant1 = $_POST['etudiant'];
        $professeur1 = $_POST['professeur'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $observations = $_POST['observations'];

        //récupérer les numéros des étudiants, professeurs et entreprises
        $entreprise = getEntreprise($pdo, $entreprise1);
        $etudiant = getEtudiant($pdo, $etudiant1);
        $professeur = getProfesseur($pdo, $professeur1);
        
        // Insérer les données dans la base de données
        insertInscription($pdo, $entreprise, $etudiant, $professeur, $date_debut, $date_fin, $type, $description, $observations);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 
?>