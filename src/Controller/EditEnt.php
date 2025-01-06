<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        // Mise à jour d'une entreprise
        try {
            $num_entreprise = $_POST['num_entreprise'];
            $raison_sociale = $_POST['raison_sociale'];
            $nom_contact = $_POST['nom_contact'];
            $nom_resp = $_POST['nom_resp'];
            $rue_entreprise = $_POST['rue_entreprise'];
            $cp_entreprise = $_POST['cp_entreprise'];
            $ville_entreprise = $_POST['ville_entreprise'];
            $tel_entreprise = $_POST['tel_entreprise'];
            $fax_entreprise = $_POST['fax_entreprise'];
            $email = $_POST['email'];
            $observation = $_POST['observation'];
            $site_entreprise = $_POST['site_entreprise'];
            $niveau = $_POST['niveau'];
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            updateEntreprise($pdo, $num_entreprise, $raison_sociale, $nom_contact, $nom_resp, $rue_entreprise, $cp_entreprise, $ville_entreprise, $tel_entreprise, $fax_entreprise, $email, $observation, $site_entreprise, $niveau, $en_activite);
            $_SESSION['edit_message'] = "Entreprise modifiée avec succès.";
            header('Location: ?page=entreprise&edit=1');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la modification de l'entreprise : " . $e->getMessage();
            header('Location: ?page=entreprise');
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['num_entreprise'])) {
    // Récupération des détails d'une entreprise
    try {
        $num_entreprise = $_GET['num_entreprise'];
        $entreprise = getEntrepriseInfo($pdo, $num_entreprise);
        $data = [
            'routes' => $routes,
            'entreprise' => $entreprise,
            'current_page' => 'editEnt',
            'error' => isset($error) ? $error : null,
        ];
        echo $twig->render('EditEnt.twig', $data);
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur lors de la récupération des informations de l'entreprise : " . $e->getMessage();
        header('Location: ?page=entreprise');
        exit;
    }
} else {
    header('Location: ?page=entreprise');
    exit;
}
?>