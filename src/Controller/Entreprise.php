<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        // Suppression d'une entreprise
        try {
            $id_entreprise = $_POST['id_entreprise'];
            deleteEntreprise($pdo, $id_entreprise);
            header('Location: ?page=entreprise');
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
            $observations = $_POST['observations'];
            $site_entreprise = $_POST['site_entreprise'];
            $niveau = $_POST['niveau'];
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            // Appel à la fonction pour insérer l'entreprise
            insertEntreprise($pdo, $raison_sociale, $nom_resp, $rue_entreprise, $cp_entreprise, $ville_entreprise, $site_entreprise);

            // Rediriger vers la page des entreprises après l'ajout
            header('Location: ?page=entreprise');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Récupérer les entreprises pour les afficher dans la liste
$entreprises = getEntreprises1($pdo);

// Passer les données et les routes dans un tableau
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises,
    'error' => isset($error) ? $error : null,
];

// Retourner les données
return $data;
?>