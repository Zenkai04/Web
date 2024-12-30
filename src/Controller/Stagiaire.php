<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

// Suppression d'un étudiant
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $num_etudiant = $_POST['num_etudiant'];
        deleteEtudiant($pdo, $num_etudiant);
        header('Location: ?page=stagiaire');
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_etudiant = $_POST['nom_etudiant'];
        $prenom_etudiant = $_POST['prenom_etudiant'];
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];
        $num_classe = $_POST['num_classe'];
        $en_activite = isset($_POST['en_activite']) ? 1 : 0;

        // Appel à la fonction pour insérer l'étudiant
        insertEtudiant($pdo, $nom_etudiant, $prenom_etudiant, $login, $mdp, $num_classe, $en_activite);

        // Rediriger vers la page des stagiaires après l'ajout
        header('Location: ?page=stagiaire');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupérer les données depuis la base de données
$etudiants = getEtudiants2($pdo);

// Passer les données et les routes dans un tableau
$data = [
    'routes' => $routes,
    'etudiants' => $etudiants,
    'error' => isset($error) ? $error : null
];

// Retourner les données
return $data;
?>