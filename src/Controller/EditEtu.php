<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        // Mise à jour d'un stagiaire
        try {
            $num_etudiant = $_POST['num_etudiant'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];
            $en_activite = isset($_POST['en_activite']) ? 1 : 0;

            updateEtudiant($pdo, $num_etudiant, $nom, $prenom, $login, $mdp, $en_activite);
            header('Location: ?page=stagiaire&success=1');
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
        $classes = getClasses($pdo);
        $data = [
            'routes' => $routes,
            'etudiant' => $etudiant,
            'classes' => $classes,
            'current_page' => 'editEtu',
            'error' => isset($error) ? $error : null
        ];
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
