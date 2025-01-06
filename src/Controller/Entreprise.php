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
        // Suppression d'une entreprise
        try {
            $num_entreprise = $_POST['num_entreprise'];
            deleteEntreprise($pdo, $num_entreprise);
            $_SESSION['success_message'] = "Entreprise supprimée avec succès.";
            header('Location: ?page=entreprise');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'entreprise : " . $e->getMessage();
            header('Location: ?page=entreprise');
            exit;
        }
    } elseif ($action === 'add') {
        // Ajout d'une entreprise
        try {
            $raison_sociale = $_POST['raison_sociale'];
            $nom_contact = $_POST['nom_contact'];
            $nom_resp = $_POST['nom_resp'];
            $rue_entreprise = $_POST['rue_entreprise'];
            $cp_entreprise = $_POST['cp_entreprise'];
            $ville_entreprise = $_POST['ville_entreprise'];
            $tel_entreprise = $_POST['tel_entreprise'];
            $fax_entreprise = $_POST['fax_entreprise'];
            $email = $_POST['email'];
            $observation = $_POST['observation'] ?? ''; // Assurez-vous que le champ observation est défini
            $site_entreprise = $_POST['site_entreprise'];
            $niveau = $_POST['niveau'];
            $specialite = $_POST['specialite']; // Utilisez num_spec ici
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            // Appel à la fonction pour insérer l'entreprise
            insertEntreprise($pdo, $raison_sociale, $nom_contact, $nom_resp, $rue_entreprise, $cp_entreprise, $ville_entreprise, $tel_entreprise, $fax_entreprise, $email, $observation, $site_entreprise, $niveau, $specialite, $en_activite);

            $_SESSION['success_message'] = "Entreprise ajoutée avec succès.";
            header('Location: ?page=entreprise');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de l'entreprise : " . $e->getMessage();
            header('Location: ?page=entreprise');
            exit;
        }
    } elseif ($action === 'search') {
        // Recherche d'une entreprise
        $search_criteria = $_POST['search_criteria'] ?? '';
        $search_value = '';

        if ($search_criteria === 'raison_sociale') {
            $search_value = $_POST['search_raison_sociale'] ?? '';
        } elseif ($search_criteria === 'libelle') {
            $search_value = $_POST['search_libelle'] ?? '';
        } elseif ($search_criteria === 'nom_contact') {
            $search_value = $_POST['search_nom_contact'] ?? '';
        }

        $entreprises = searchEntreprises($pdo, $search_criteria, $search_value);
        $specialites = getSpecialites($pdo);
    }
} else {
    // Récupération des entreprises et des spécialités
    $entreprises = getEntreprises($pdo);
    $specialites = getSpecialites($pdo);
}
// En cas d'erreur
$entreprises = $entreprises ?? [];
$specialites = $specialites ?? [];

$data = [
    'routes' => $routes,
    'entreprises' => $entreprises,
    'specialites' => $specialites,
    'current_page' => 'entreprise',
    'success' => isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null,
    'error' => isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null,
];

// Retourner les données pour l'inclusion
return $data;
?>