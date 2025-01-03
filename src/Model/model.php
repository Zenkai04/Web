<?php
require_once('connect.php');

// Fonction pour récupérer les entreprises avec leurs spécialités
function getEntreprises($pdo) {
    $query = '
        SELECT 
            e.num_entreprise,
            e.raison_sociale,
            GROUP_CONCAT(DISTINCT e.nom_contact SEPARATOR ", ") AS contacts,
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

// Fonction pour récupérer les spécialités
function getSpecialites($pdo) {
    $query = $pdo->prepare("SELECT num_spec, libelle FROM specialite");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
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

// Fonction pour récupérer tous les étudiants ainsi que leur entreprise et leur professeur
function getEtudiants2($pdo) {
    $query = '
        SELECT 
            etu.num_etudiant, 
            etu.nom_etudiant, 
            etu.prenom_etudiant, 
            GROUP_CONCAT(e.raison_sociale SEPARATOR ", ") AS entreprises, 
            GROUP_CONCAT(p.nom_prof SEPARATOR ", ") AS professeurs
        FROM 
            etudiant etu
        LEFT JOIN 
            stage s ON etu.num_etudiant = s.num_etudiant
        LEFT JOIN 
            entreprise e ON s.num_entreprise = e.num_entreprise
        LEFT JOIN 
            professeur p ON s.num_prof = p.num_prof
        GROUP BY 
            etu.num_etudiant, etu.nom_etudiant, etu.prenom_etudiant
        ORDER BY 
            etu.nom_etudiant
    ';
    $result = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

// Fonction pour supprimer un étudiant
function deleteEtudiant($pdo, $num_etudiant) {
    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Supprimer les enregistrements dans la table stage qui référencent l'étudiant
        $query = $pdo->prepare("DELETE FROM stage WHERE num_etudiant = :num_etudiant");
        $query->bindParam(':num_etudiant', $num_etudiant);
        $query->execute();

        // Supprimer l'étudiant
        $query = $pdo->prepare("DELETE FROM etudiant WHERE num_etudiant = :num_etudiant");
        $query->bindParam(':num_etudiant', $num_etudiant);
        $query->execute();

        // Valider la transaction
        $pdo->commit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        throw new Exception('Erreur lors de la suppression : ' . $e->getMessage());
    }
}

// Fonction pour supprimer une entreprise
function deleteEntreprise($pdo, $num_entreprise) {
    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Supprimer les enregistrements dans la table stage qui référencent l'entreprise
        $query = $pdo->prepare("DELETE FROM stage WHERE num_entreprise = :num_entreprise");
        $query->bindParam(':num_entreprise', $num_entreprise);
        $query->execute();

        // Supprimer les enregistrements dans la table spec_entreprise qui référencent l'entreprise
        $query = $pdo->prepare("DELETE FROM spec_entreprise WHERE num_entreprise = :num_entreprise");
        $query->bindParam(':num_entreprise', $num_entreprise);
        $query->execute();

        // Supprimer l'entreprise
        $query = $pdo->prepare("DELETE FROM entreprise WHERE num_entreprise = :num_entreprise");
        $query->bindParam(':num_entreprise', $num_entreprise);
        $query->execute();

        // Valider la transaction
        $pdo->commit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        throw new Exception('Erreur lors de la suppression : ' . $e->getMessage());
    }
}

// Fonction pour ajouter une entreprise
function insertEntreprise($pdo, $raison_sociale, $nom_contact, $nom_resp, $rue_entreprise, $cp_entreprise, $ville_entreprise, $tel_entreprise, $fax_entreprise, $email, $observation, $site_entreprise, $niveau, $num_spec, $en_activite) {
    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Insérer la nouvelle entreprise dans la table entreprise
        $query = $pdo->prepare("
            INSERT INTO entreprise (raison_sociale, nom_contact, nom_resp, rue_entreprise, cp_entreprise, ville_entreprise, tel_entreprise, fax_entreprise, email, observation, site_entreprise, niveau, en_activite)
            VALUES (:raison_sociale, :nom_contact, :nom_resp, :rue_entreprise, :cp_entreprise, :ville_entreprise, :tel_entreprise, :fax_entreprise, :email, :observation, :site_entreprise, :niveau, :en_activite)
        ");
        $query->bindParam(':raison_sociale', $raison_sociale);
        $query->bindParam(':nom_contact', $nom_contact);
        $query->bindParam(':nom_resp', $nom_resp);
        $query->bindParam(':rue_entreprise', $rue_entreprise);
        $query->bindParam(':cp_entreprise', $cp_entreprise);
        $query->bindParam(':ville_entreprise', $ville_entreprise);
        $query->bindParam(':tel_entreprise', $tel_entreprise);
        $query->bindParam(':fax_entreprise', $fax_entreprise);
        $query->bindParam(':email', $email);
        $query->bindParam(':observation', $observation);
        $query->bindParam(':site_entreprise', $site_entreprise);
        $query->bindParam(':niveau', $niveau);
        $query->bindParam(':en_activite', $en_activite);
        $query->execute();

        // Récupérer le numéro de l'entreprise nouvellement insérée
        $num_entreprise = $pdo->lastInsertId();

        // Insérer le numéro de l'entreprise et le numéro de la spécialité dans la table spec_entreprise
        $query = $pdo->prepare("
            INSERT INTO spec_entreprise (num_entreprise, num_spec)
            VALUES (:num_entreprise, :num_spec)
        ");
        $query->bindParam(':num_entreprise', $num_entreprise);
        $query->bindParam(':num_spec', $num_spec);
        $query->execute();

        // Valider la transaction
        $pdo->commit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        throw new Exception('Erreur lors de l\'insertion : ' . $e->getMessage());
    }
}

// Fonction pour récupérer les classes
function getClasses($pdo) {
    $query = $pdo->prepare("SELECT num_classe, nom_classe FROM classe");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction qui retourne toutes les informations d'une classe à partir de son numéro
function getClasseById($pdo, $num_classe) {
    try {
        $query = $pdo->prepare("SELECT * FROM classe WHERE num_classe = :num_classe");
        $query->bindParam(':num_classe', $num_classe);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la récupération de la classe : ' . $e->getMessage());
    }
}

// Fonction pour ajouter un étudiant
function insertEtudiant($pdo, $nom_etudiant, $prenom_etudiant, $login, $mdp, $annee_obtention, $num_classe, $en_activite) {
    try {
        $query = $pdo->prepare("
            INSERT INTO etudiant (nom_etudiant, prenom_etudiant, annee_obtention, login, mdp, num_classe, en_activite)
            VALUES (:nom_etudiant, :prenom_etudiant, :annee_obtention, :login, :mdp, :num_classe, :en_activite)
        ");
        $query->bindParam(':nom_etudiant', $nom_etudiant);
        $query->bindParam(':prenom_etudiant', $prenom_etudiant);
        $query->bindParam(':login', $login);
        $query->bindParam(':mdp', $mdp);
        $query->bindParam(':annee_obtention', $annee_obtention);
        $query->bindParam(':num_classe', $num_classe);
        $query->bindParam(':en_activite', $en_activite);
        $query->execute();
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de l\'insertion : ' . $e->getMessage());
    }
}

// Fonction pour chercher une entreprise
function searchEntreprises($pdo, $criteria, $value) {
    try {
        $allowedCriteria = ['raison_sociale', 'libelle', 'nom_contact'];
        if (!in_array($criteria, $allowedCriteria)) {
            throw new Exception("Critère de recherche non valide : {$criteria}");
        }

        $queryStr = '
            SELECT 
                e.num_entreprise,
                e.raison_sociale,
                e.nom_contact AS contacts,
                e.rue_entreprise,
                e.cp_entreprise,
                e.ville_entreprise,
                e.site_entreprise,
                GROUP_CONCAT(s.libelle SEPARATOR ", ") AS specialites
            FROM entreprise e
            LEFT JOIN spec_entreprise es ON e.num_entreprise = es.num_entreprise
            LEFT JOIN specialite s ON es.num_spec = s.num_spec
        ';

        if ($criteria === 'libelle') {
            $queryStr .= "
            GROUP BY 
                e.num_entreprise, e.raison_sociale
            HAVING
                specialites LIKE :value
            ";
        } else {
            $queryStr .= "
            WHERE e.{$criteria} LIKE :value
            GROUP BY 
                e.num_entreprise, e.raison_sociale
            ";
        }

        $query = $pdo->prepare($queryStr);
        $value = '%' . $value . '%';
        $query->bindParam(':value', $value);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la recherche : ' . $e->getMessage());
    }
}

// Fonction pour chercher un étudiant
function searchEtudiants($pdo, $criteria, $value) {
    try {
        $allowedCriteria = ['nom_etudiant', 'prenom_etudiant', 'raison_sociale', 'nom_prof'];
        if (!in_array($criteria, $allowedCriteria)) {
            throw new Exception("Critère de recherche non valide : {$criteria}");
        }

        $queryStr = '
            SELECT 
                etu.num_etudiant, 
                etu.nom_etudiant, 
                etu.prenom_etudiant, 
                GROUP_CONCAT(e.raison_sociale SEPARATOR ", ") AS entreprises, 
                GROUP_CONCAT(p.nom_prof SEPARATOR ", ") AS professeurs
            FROM etudiant etu
            LEFT JOIN stage s ON etu.num_etudiant = s.num_etudiant
            LEFT JOIN entreprise e ON s.num_entreprise = e.num_entreprise
            LEFT JOIN professeur p ON s.num_prof = p.num_prof
        ';

        if ($criteria === 'raison_sociale') {
            $queryStr .= "
            WHERE 
                e.raison_sociale LIKE :value
            GROUP BY 
                etu.num_etudiant, etu.nom_etudiant, etu.prenom_etudiant
            ";
        } elseif ($criteria === 'nom_prof') {
            $queryStr .= "
            WHERE 
                p.nom_prof LIKE :value
            GROUP BY 
                etu.num_etudiant, etu.nom_etudiant, etu.prenom_etudiant
            ";
        } else {
            $queryStr .= "
            WHERE etu.{$criteria} LIKE :value
            GROUP BY 
                etu.num_etudiant, etu.nom_etudiant, etu.prenom_etudiant
            ";
        }

        $query = $pdo->prepare($queryStr);
        $value = '%' . $value . '%';
        $query->bindParam(':value', $value);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la recherche : ' . $e->getMessage());
    }
}

// Fonction qui retourne toutes les informations d'un étudiant
function getEtudiantInfo($pdo, $num_etudiant) {
    $query = $pdo->prepare("
        SELECT 
            num_etudiant, 
            nom_etudiant, 
            prenom_etudiant, 
            login, 
            mdp, 
            num_classe, 
            en_activite
        FROM 
            etudiant
        WHERE 
            num_etudiant = :num_etudiant
    ");
    $query->bindParam(':num_etudiant', $num_etudiant);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Fonction qui retourne toutes les informations d'une entreprise
function getEntrepriseInfo($pdo, $num_entreprise) {
    $query = $pdo->prepare("
        SELECT 
            e.num_entreprise, 
            e.raison_sociale, 
            e.nom_contact, 
            e.nom_resp, 
            e.rue_entreprise, 
            e.cp_entreprise, 
            e.ville_entreprise, 
            e.tel_entreprise, 
            e.fax_entreprise, 
            e.email, 
            e.observation, 
            e.site_entreprise, 
            e.niveau, 
            e.en_activite,
            GROUP_CONCAT(s.libelle SEPARATOR ', ') AS specialites
        FROM 
            entreprise e 
        LEFT JOIN 
            spec_entreprise se ON e.num_entreprise = se.num_entreprise
        LEFT JOIN 
            specialite s ON se.num_spec = s.num_spec
        WHERE 
            e.num_entreprise = :num_entreprise
    ");
    $query->bindParam(':num_entreprise', $num_entreprise);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un étudiant
function updateEtudiant($pdo, $num_etudiant, $nom, $prenom, $login, $mdp, $en_activite) {
    try {
        $query = $pdo->prepare("
            UPDATE 
                etudiant
            SET 
                nom_etudiant = :nom,
                prenom_etudiant = :prenom,
                login = :login,
                mdp = :mdp,
                en_activite = :en_activite
            WHERE 
                num_etudiant = :num_etudiant
        ");
        $query->bindParam(':num_etudiant', $num_etudiant);
        $query->bindParam(':nom', $nom);
        $query->bindParam(':prenom', $prenom);
        $query->bindParam(':login', $login);
        $query->bindParam(':mdp', $mdp);
        $query->bindParam(':en_activite', $en_activite);
        $query->execute();
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}

// Fonction pour mettre à jour une entreprise
function updateEntreprise($pdo, $num_entreprise, $raison_sociale, $nom_contact, $nom_responeable, $rue_entreprise, $cp_entreprise, $ville_entreprise, $tel_entreprise, $fax_entreprise, $email, $observation, $site_entreprise, $niveau, $en_activite) {
    try {
        $query = $pdo->prepare("
            UPDATE 
                entreprise
            SET 
                raison_sociale = :raison_sociale,
                nom_contact = :nom_contact,
                nom_resp = :nom_resp,
                rue_entreprise = :rue_entreprise,
                cp_entreprise = :cp_entreprise,
                ville_entreprise = :ville_entreprise,
                tel_entreprise = :tel_entreprise,
                fax_entreprise = :fax_entreprise,
                email = :email,
                observation = :observation,
                site_entreprise = :site_entreprise,
                niveau = :niveau,
                en_activite = :en_activite
            WHERE 
                num_entreprise = :num_entreprise
        ");
        $query->bindParam(':num_entreprise', $num_entreprise);
        $query->bindParam(':raison_sociale', $raison_sociale);
        $query->bindParam(':nom_contact', $nom_contact);
        $query->bindParam(':nom_resp', $nom_responeable);
        $query->bindParam(':rue_entreprise', $rue_entreprise);
        $query->bindParam(':cp_entreprise', $cp_entreprise);
        $query->bindParam(':ville_entreprise', $ville_entreprise);
        $query->bindParam(':tel_entreprise', $tel_entreprise);
        $query->bindParam(':fax_entreprise', $fax_entreprise);
        $query->bindParam(':email', $email);
        $query->bindParam(':observation', $observation);
        $query->bindParam(':site_entreprise', $site_entreprise);
        $query->bindParam(':niveau', $niveau);
        $query->bindParam(':en_activite', $en_activite);
        $query->execute();
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}

// Fonction qui retourne les stages ainsi que les étudiants et leur classe des stages selon l'entreprise
function getStages($pdo, $num_entreprise) {
    $query = $pdo->prepare("
        SELECT 
            s.num_stage, 
            s.num_etudiant, 
            s.num_prof, 
            s.debut_stage, 
            s.fin_stage, 
            s.type_stage, 
            s.desc_projet, 
            s.observation_stage, 
            e.nom_etudiant, 
            e.prenom_etudiant, 
            c.nom_classe
        FROM 
            stage s
        JOIN 
            etudiant e ON s.num_etudiant = e.num_etudiant
        JOIN 
            classe c ON e.num_classe = c.num_classe
        WHERE 
            s.num_entreprise = :num_entreprise
    ");
    $query->bindParam(':num_entreprise', $num_entreprise);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer un étudiant par login et mot de passe
function getEtudiantByLogin($pdo, $login, $password) {
    try {
        $query = $pdo->prepare("SELECT * FROM etudiant WHERE login = :login AND mdp = :password");
        $query->bindParam(':login', $login);
        $query->bindParam(':password', $password);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la récupération de l\'étudiant : ' . $e->getMessage());
    }
}

// Fonction pour récupérer un professeur par login et mot de passe
function getProfesseurByLogin($pdo, $login, $password) {
    try {
        $query = $pdo->prepare("SELECT * FROM professeur WHERE login = :login AND mdp = :password");
        $query->bindParam(':login', $login);
        $query->bindParam(':password', $password);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Erreur lors de la récupération du professeur : ' . $e->getMessage());
    }
}
?>