<?php
require_once('connect.php');

// Fonction pour récupérer les entreprises avec leurs spécialités
function getEntreprises($pdo) {
    $query = '
        SELECT e.num_entreprise, e.raison_sociale, e.nom_resp, e.rue_entreprise, e.cp_entreprise, e.ville_entreprise, e.site_entreprise, sp.libelle
        FROM entreprise e
        INNER JOIN spec_entreprise se ON e.num_entreprise = se.num_entreprise
        INNER JOIN specialite sp ON se.num_spec = sp.num_spec
    ';
    $result = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

    // Regrouper les spécialités par entreprise
    $entreprises = [];
    foreach ($result as $row) {
        $num_entreprise = $row['num_entreprise'];
        if (!isset($entreprises[$num_entreprise])) {
            $entreprises[$num_entreprise] = [
                'num_entreprise' => $row['num_entreprise'],
                'raison_sociale' => $row['raison_sociale'],
                'nom_resp' => $row['nom_resp'],
                'rue_entreprise' => $row['rue_entreprise'],
                'cp_entreprise' => $row['cp_entreprise'],
                'ville_entreprise' => $row['ville_entreprise'],
                'site_entreprise' => $row['site_entreprise'],
                'specialites' => []
            ];
        }
        $entreprises[$num_entreprise]['specialites'][] = $row['libelle'];
    }

    return array_values($entreprises);
}

// Fonction pour insérer les données d'inscription dans la base de données
function insertInscription($pdo, $entreprise, $etudiant, $professeur, $date_debut, $date_fin, $type, $description, $observations) {
    try {
        $query = $pdo->prepare("
            INSERT INTO stage (num_entreprise, num_etudiant, num_prof, debut_stage, fin_stage, type_stage, desc_projet, observation_stage)
            VALUES (:num_entreprise, :num_etudiant, :num_prof, :debut_stage, :fin_stage, :type_stage, :desc_projet, :observation_stage)
        ");
        $query->execute([
            ':num_entreprise' => $entreprise,
            ':num_etudiant' => $etudiant,
            ':num_prof' => $professeur,
            ':debut_stage' => $date_debut,
            ':fin_stage' => $date_fin,
            ':type_stage' => $type,
            ':desc_projet' => $description,
            ':observation_stage' => $observations,
        ]);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de l\'insertion : ' . $e->getMessage());
    }
}
?>