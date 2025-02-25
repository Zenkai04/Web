<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/twig.php');
require_once(__DIR__ . '/../Model/close.php');
require_once(__DIR__ . '/../../config/routes.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $entreprise = $_POST['entreprise'];
        $etudiant = $_POST['etudiant'];
        $professeur = $_POST['professeur'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $observations = $_POST['observations'];

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

        // Rediriger vers la page d'inscription avec un message de succès
        header('Location: ?page=inscription&success=1');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
        header('Location: ?page=inscription&error=' . urlencode($error));
        exit;
    }
} else {
    // Récupération des entreprises, étudiants et professeurs
    $entreprises = getEntreprises($pdo);
    $etudiants = getEtudiants($pdo);
    $professeurs = getProfesseurs($pdo);

    $data = [
        'routes' => $routes,
        'entreprises' => $entreprises,
        'etudiants' => $etudiants,
        'professeurs' => $professeurs,
        'current_page' => 'inscription',
        'success' => isset($_GET['success']),
        'error' => isset($_GET['error']) ? $_GET['error'] : null,
    ];

    // Retourner les données pour l'inclusion
    return $data;
}
?>