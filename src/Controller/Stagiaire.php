<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        // Suppression d'un étudiant
        try {
            $num_etudiant = $_POST['num_etudiant'];
            deleteEtudiant($pdo, $num_etudiant);
            // Rediriger vers la page des stagiaires après la suppression
            header('Location: /projets/Web/public/?page=stagiaire&delete=1');
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } elseif ($action === 'add') {
        // Ajout d'un étudiant
        try {
            $nom_etudiant = $_POST['nom_etudiant'];
            $prenom_etudiant = $_POST['prenom_etudiant'];
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];
            $annee_obtention = $_POST['annee_obtention'];
            $num_classe = $_POST['num_classe'];
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            // Appel à la fonction pour insérer l'étudiant
            insertEtudiant($pdo, $nom_etudiant, $prenom_etudiant, $login, $mdp, $annee_obtention, $num_classe, $en_activite);

            // Rediriger vers la page des stagiaires après l'ajout avec succès
            header('Location: /projets/Web/public/?page=stagiaire&success=1');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($action === 'search') {
        // Recherche d'un étudiant
        $search_criteria = $_POST['search_criteria'] ?? '';
        $search_value = '';

        if ($search_criteria === 'nom_etudiant') {
            $search_value = $_POST['search_nom_etudiant'] ?? '';
        } elseif ($search_criteria === 'prenom_etudiant') {
            $search_value = $_POST['search_prenom_etudiant'] ?? '';
        } elseif ($search_criteria === 'nom_prof') {
            $search_value = $_POST['search_nom_prof'] ?? '';
        } elseif ($search_criteria === 'raison_sociale') {
            $search_value = $_POST['search_raison_sociale'] ?? '';
        }

        $etudiants = searchEtudiants($pdo, $search_criteria, $search_value);
        $classes = getClasses($pdo);
    }
} else {
    // Récupération des stagiaires et des classes
    $etudiants = getEtudiants2($pdo);
    $classes = getClasses($pdo);
}

// Assurez-vous que les variables sont définies même en cas d'erreur
$etudiants = $etudiants ?? [];
$classes = $classes ?? [];

$data = [
    'routes' => $routes,
    'etudiants' => $etudiants,
    'classes' => $classes,
    'success' => isset($_GET['success']),
    'delete' => isset($_GET['delete']),
    'current_page' => 'stagiaire',
    'error' => isset($error) ? $error : null,
];

// Retourner les données pour l'inclusion
return $data;
?>