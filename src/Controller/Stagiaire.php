<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Restrict actions for students
    if ($_SESSION['role'] === 'etudiant' && in_array($action, ['delete', 'add', 'update'])) {
        $_SESSION['error_message'] = "Vous n'avez pas les droits nécessaires pour effectuer cette action.";
        header('Location: ?page=home');
        exit;
    }

    if ($action === 'delete') {
        // Suppression d'un étudiant
        try {
            $num_etudiant = $_POST['num_etudiant'];
            deleteEtudiant($pdo, $num_etudiant);
            $_SESSION['delete_message'] = "Étudiant supprimé avec succès.";
            header('Location: ?page=stagiaire&delete=1');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'étudiant : " . $e->getMessage();
            header('Location: ?page=stagiaire');
            exit;
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

            $_SESSION['success_message'] = "Étudiant ajouté avec succès.";
            header('Location: ?page=stagiaire&success=1');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de l'étudiant : " . $e->getMessage();
            header('Location: ?page=stagiaire');
            exit;
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

// En cas d'erreur
$etudiants = $etudiants ?? [];
$classes = $classes ?? [];

$data = [
    'routes' => $routes,
    'etudiants' => $etudiants,
    'classes' => $classes,
    'success' => isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null,
    'delete' => isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : null,
    'edit' => isset($_SESSION['edit_message']) ? $_SESSION['edit_message'] : null,
    'current_page' => 'stagiaire',
    'error' => isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null,
];

// Effacer le message de succès après l'affichage
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
unset($_SESSION['delete_message']);
unset($_SESSION['edit_message']);

// Retourner les données pour l'inclusion
return $data;
?>