<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        // Suppression d'un stagiaire
        try {
            $num_etudiant = $_POST['num_etudiant'];
            deleteStagiaire($pdo, $num_etudiant);
            header('Location: ?page=stagiaire');
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } elseif ($action === 'update') {
        // Mise à jour d'un stagiaire
        try {
            $num_etudiant = $_POST['num_etudiant'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];
            $num_classe = $_POST['num_classe'];
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            // Log des données reçues
            error_log("Updating student: $num_etudiant, $nom, $prenom, $login, $mdp, $num_classe, $en_activite");

            updateEtudiant($pdo, $num_etudiant, $nom, $prenom, $login, $mdp, $num_classe, $en_activite);
            header('Location: ?page=stagiaire');
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['num_etudiant'])) {
    // Récupération des détails d'un stagiaire
    try {
        $num_etudiant = $_GET['num_etudiant'];
        $etudiant = getEtudiantInfo($pdo, $num_etudiant);
        // Passer les données et les routes dans un tableau
        $data = [
            'routes' => $routes,
            'etudiant' => $etudiant,
            'error' => isset($error) ? $error : null,
        ];
        // Charger le template Twig
        echo $twig->render('EditEtu.twig', $data);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    header('Location: ?page=stagiaire');
    exit;
}
?>