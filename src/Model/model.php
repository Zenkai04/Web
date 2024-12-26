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

// Fonction pour récupérer les étudiants
function getEtudiants($pdo) {
    $query = $pdo->prepare("SELECT num_etudiant, nom_etudiant, prenom_etudiant FROM etudiant");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les professeurs
function getProfesseurs($pdo) {
    $query = $pdo->prepare("SELECT num_prof, nom_prof FROM professeur");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les entreprises
function getEntreprises2($pdo) {
    $query = $pdo->prepare("SELECT num_entreprise, raison_sociale FROM entreprise");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les numéros des étudiants à partir de leur nom et prénom
function getEtudiant($pdo, $etudiant) {
    $nom = substr($etudiant, 0, strpos($etudiant, ' '));
    $prenom = substr($etudiant, strpos($etudiant, ' ') + 1);
    $query = $pdo->prepare("SELECT num_etudiant FROM etudiant WHERE nom_etudiant = :nom AND prenom_etudiant = :prenom");
    $query->bindParam(':nom', $nom);
    $query->bindParam(':prenom', $prenom);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['num_etudiant'] : null;
}

// Fonction pour récupérer les numéros des professeurs à partir de leur nom
function getProfesseur($pdo, $professeur) {
    $query = $pdo->prepare("SELECT num_prof FROM professeur WHERE nom_prof = :nom_prof");
    $query->bindParam(':nom_prof', $professeur);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['num_prof'] : null;
}

// Fonction pour récupérer les numéros des entreprises à partir de leur raison sociale
function getEntreprise($pdo, $entreprise) {
    $query = $pdo->prepare("SELECT num_entreprise FROM entreprise WHERE raison_sociale = :raison_sociale");
    $query->bindParam(':raison_sociale', $entreprise);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['num_entreprise'] : null;
}

// Fonction pour insérer les données d'inscription dans la base de données
function insertInscription($pdo, $entreprise, $etudiant, $professeur, $date_debut, $date_fin, $type, $description, $observations) {
    try {
        $query = $pdo->prepare("
            INSERT INTO stage (num_entreprise, num_etudiant, num_prof, debut_stage, fin_stage, type_stage, desc_projet, observation_stage)
            VALUES (:num_entreprise, :num_etudiant, :num_prof, :debut_stage, :fin_stage, :type_stage, :desc_projet, :observation_stage)
        ");
        $query->bindParam(':num_entreprise', $entreprise);
        $query->bindParam(':num_etudiant', $etudiant);
        $query->bindParam(':num_prof', $professeur);
        $query->bindParam(':debut_stage', $date_debut);
        $query->bindParam(':fin_stage', $date_fin);
        $query->bindParam(':type_stage', $type);
        $query->bindParam(':desc_projet', $description);
        $query->bindParam(':observation_stage', $observations);
        $query->execute();
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de l\'insertion : ' . $e->getMessage());
    }
}
?>