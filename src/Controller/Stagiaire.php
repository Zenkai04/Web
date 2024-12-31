<?php
require_once(__DIR__ . '/../Model/connect.php');
require_once(__DIR__ . '/../Model/model.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $input = json_decode(file_get_contents('php://input'), true);

    $num_etudiant = $input['num_etudiant'];
    $nom_etudiant = $input['nom'] ?? null;
    $prenom_etudiant = $input['prenom'] ?? null;
    $entreprise = $input['entreprise'] ?? null;
    $professeur = $input['professeur'] ?? null;

    try {
        // Mise à jour des données
        $stmt = $pdo->prepare("
            UPDATE etudiant
            SET nom_etudiant = :nom,
                prenom_etudiant = :prenom,
                entreprises = :entreprise,
                professeurs = :professeur
            WHERE num_etudiant = :num_etudiant
        ");

        $stmt->execute([
            ':nom' => $nom_etudiant,
            ':prenom' => $prenom_etudiant,
            ':entreprise' => $entreprise,
            ':professeur' => $professeur,
            ':num_etudiant' => $num_etudiant
        ]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    if ($action === 'delete') {
        // Suppression d'un étudiant
        try {
            $num_etudiant = $_POST['num_etudiant'];
            deleteEtudiant($pdo, $num_etudiant);
            header('Location: ?page=stagiaire');
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } elseif ($action === 'add') {
        // Ajout d'un étudiant
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
}

// Récupérer les étudiants pour les afficher dans la liste
$etudiants = getEtudiants2($pdo);

// Passer les données et les routes dans un tableau
$data = [
    'routes' => $routes,
    'etudiants' => $etudiants,
    'error' => isset($error) ? $error : null
];

// Retourner les données
return $data;