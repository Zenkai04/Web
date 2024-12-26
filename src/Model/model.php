<?php
require_once('connect.php');

// Fonction pour récupérer les entreprises avec leurs spécialités
function getEntreprises($pdo) {
    $query = '
        SELECT 
            e.num_entreprise,
            e.raison_sociale,
            e.nom_resp,
            e.rue_entreprise,
            e.cp_entreprise,
            e.ville_entreprise,
            e.site_entreprise,
    GROUP_CONCAT(s.libelle SEPARATOR ", ") AS specialites
        FROM 
            entreprise e
        LEFT JOIN 
            spec_entreprise se ON e.num_entreprise = se.num_entreprise
        LEFT JOIN 
            specialite s ON se.num_spec = s.num_spec
        GROUP BY 
            e.num_entreprise, e.raison_sociale
    ';
    $result = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

// Fonction pour récupérer les numéros des étudiants à partir de leur nom et prénom
function getEtudiant($pdo, $etudiant) {
    list($nom, $prenom) = explode(' ', $etudiant, 2);
    $query = $pdo->prepare('SELECT num_etudiant FROM etudiant WHERE nom_etudiant = :nom AND prenom_etudiant = :prenom');
    $query->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['num_etudiant'];
}

// Fonction pour récupérer les numéros des professeurs à partir de leur nom
function getProfesseur($pdo, $professeur) {
    $query = $pdo->prepare('SELECT num_prof FROM professeur WHERE nom_prof = :nom_prof');
    $query->execute([
        ':nom_prof' => $professeur,
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['num_prof'];
}

// Fonction pour récupérer les numéros des entreprises à partir de leur raison sociale
function getEntreprise($pdo, $entreprise) {
    $query = $pdo->prepare('SELECT num_entreprise FROM entreprise WHERE raison_sociale = :raison_sociale');
    $query->execute([
        ':raison_sociale' => $entreprise,
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['num_entreprise'];
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