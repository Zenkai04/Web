<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        // Suppression d'une entreprise
        try {
            $num_entreprise = $_POST['num_entreprise'];
            deleteEntreprise($pdo, $num_entreprise);
            // Rediriger vers la page des entreprises après l'ajout avec succès
            header('Location: /projets/Web/public/?page=entreprise&delete=1');
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
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

            // Rediriger vers la page des entreprises après l'ajout avec succès
            header('Location: /projets/Web/public/?page=entreprise&success=1');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
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
    $entreprises = getEntreprises1($pdo);
    $specialites = getSpecialites($pdo);
}

// Assurez-vous que les variables sont définies même en cas d'erreur
$entreprises = $entreprises ?? [];
$specialites = $specialites ?? [];

$data = [
    'routes' => $routes,
    'entreprises' => $entreprises,
    'specialites' => $specialites,
    'current_page' => 'entreprise',
    'success' => isset($_GET['success']),
    'delete' => isset($_GET['delete']),
    'error' => isset($error) ? $error : null,
];

// Retourner les données pour l'inclusion
return $data;
?>