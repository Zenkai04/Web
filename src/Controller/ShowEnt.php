<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['num_entreprise'])) {
    // Récupération des détails d'une entreprise
    try {
        $num_entreprise = $_GET['num_entreprise'];
        $entreprise = getEntrepriseInfo($pdo, $num_entreprise);
        $stages = getStages($pdo, $num_entreprise);

        // Passer les données et les routes dans un tableau
        $data = [
            'routes' => $routes,
            'entreprise' => $entreprise,
            'stages' => $stages,
            'current_page' => 'showEnt',
            'error' => isset($error) ? $error : null,
        ];
        
        // Charger le template Twig
        echo $twig->render('ShowEnt.twig', $data);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    header('Location: ?page=entreprise');
    exit;
}
?>