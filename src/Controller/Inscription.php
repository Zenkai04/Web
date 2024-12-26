<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/twig.php');
require_once(__DIR__ . '/../Model/close.php');
require_once(__DIR__ . '/../../config/routes.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $entreprise = $_POST['entreprise'];
        $etudiant = $_POST['etudiant'];
        $professeur = $_POST['professeur'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $observations = $_POST['observations'];

        // Log des valeurs reçues
        error_log("Entreprise: $entreprise, Etudiant: $etudiant, Professeur: $professeur");

        // Récupérer les numéros correspondants
        //$entreprise = getEntreprise($pdo, $entreprise_nom);
        //$etudiant = getEtudiant($pdo, $etudiant_nom);
        //$professeur = getProfesseur($pdo, $professeur_nom);

        // Log des résultats des requêtes
        error_log("Numéro entreprise: $entreprise, Numéro étudiant: $etudiant, Numéro professeur: $professeur");

        // Vérifier que les numéros ne sont pas NULL
        if ($entreprise === null) {
            throw new Exception('Entreprise non trouvée');
        }
        if ($etudiant === null) {
            throw new Exception('Étudiant non trouvé');
        }
        if ($professeur === null) {
            throw new Exception('Professeur non trouvé');
        }

        // Insérer les données dans la base de données
        insertInscription($pdo, $entreprise, $etudiant, $professeur, $date_debut, $date_fin, $type, $description, $observations);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Récupérer les données nécessaires pour le formulaire d'inscription
    $entreprises = getEntreprises2($pdo);
    $etudiants = getEtudiants($pdo);
    $professeurs = getProfesseurs($pdo);

    // Passer les données et les routes dans un tableau
    $data = [
        'routes' => $routes,
        'entreprises' => $entreprises,
        'etudiants' => $etudiants,
        'professeurs' => $professeurs
    ];

    // Retourner les données
    return $data;
}
?>