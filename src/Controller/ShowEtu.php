<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['num_etudiant'])) {
    // Récupération des détails d'un étudiant
    try {
        $num_etudiant = $_GET['num_etudiant'];
        $etudiant = getEtudiantInfo($pdo, $num_etudiant);
        
        // Passer les données et les routes dans un tableau
        $data = [
            'routes' => $routes,
            'etudiant' => $etudiant,
            'current_page' => 'showEtu',
            'error' => isset($error) ? $error : null
        ];
        
        // Charger le template Twig pour afficher les données
        echo $twig->render('ShowEtu.twig', $data);
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
