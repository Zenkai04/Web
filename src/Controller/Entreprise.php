<?php
require_once('../Model/connect.php');
require_once('../Model/twig.php');
require_once('../Model/close.php');
require_once('../config/routes.php'); // Inclusion des routes

// Fonction pour récupérer les spécialités associées à une entreprise
function getSpecialitesForEntreprises($pdo, $entreprises) {
    foreach ($entreprises as &$entreprise) {
        $query = '
            SELECT sp.libelle
            FROM specialite sp
            INNER JOIN spec_entreprise se ON sp.num_spec = se.num_spec
            WHERE se.num_entreprise = ?
        ';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$entreprise['num_entreprise']]);
        $specialites = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $entreprise['specialites'] = $specialites;
    }
    return $entreprises;
}

// Récupérer les données depuis la base de données
$query = '
    SELECT e.num_entreprise, e.raison_sociale, e.nom_resp, e.rue_entreprise, e.cp_entreprise, e.ville_entreprise, e.site_entreprise
    FROM entreprise e
';
$entreprises = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Ajouter les spécialités à chaque entreprise
$entreprises = getSpecialitesForEntreprises($pdo, $entreprises);

// Passer les données et les routes dans Twig
$data = [
    'routes' => $routes,
    'entreprises' => $entreprises
];

// Afficher la page Entreprise
echo $twig->render('Entreprise.twig', $data);
?>