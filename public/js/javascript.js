document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/projets/Web/src/Controller/Inscription.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('confirmationMessage').style.display = 'block';
        } else {
            alert('Erreur lors de l\'inscription: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'inscription: ' + error.message);
    });
});

function confirmDelete(numEtudiant) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce stagiaire ?')) {
        document.getElementById('delete-form-' + numEtudiant).submit();
    }
}

function ajoutEtu() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.innerHTML = `
        <form id="add-student-form" action="?page=stagiaire" method="post">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="nom_etudiant" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom_etudiant" name="nom_etudiant" required>
            </div>
            <div class="mb-3">
                <label for="prenom_etudiant" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom_etudiant" name="prenom_etudiant" required>
            </div>
            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
            <div class="mb-3">
                <label for="num_classe" class="form-label">Numéro de classe</label>
                <input type="text" class="form-control" id="num_classe" name="num_classe" required>
            </div>
            <div class="mb-3">
                <label for="en_activite" class="form-label">En activité</label>
                <input type="checkbox" class="form-check-input" id="en_activite" name="en_activite">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Annuler</button>
        </form>
    `;
    formContainer.style.display = 'block';
}

function ajoutEnt() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.innerHTML = `
        <form id="add-enterprise-form" action="?page=entreprise" method="post">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="raison_sociale" class="form-label">Raison sociale</label>
                <input type="text" class="form-control" id="raison_sociale" name="raison_sociale" required>
            </div>
            <div class="mb-3">
                <label for="nom_contact" class="form-label">Nom du contact</label>
                <input type="text" class="form-control" id="nom_contact" name="nom_contact" required>
            </div>
            <div class="mb-3">
                <label for="nom_responeable" class="form-label">Nom du responsable</label>
                <input type="text" class="form-control" id="nom_responeable" name="nom_responeable" required>
            </div>
            <div class="mb-3">
                <label for="rue_entreprise" class="form-label">Rue de l'entreprise</label>
                <input type="text" class="form-control" id="rue_entreprise" name="rue_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="cp_entreprise" class="form-label">Code postal</label>
                <input type="text" class="form-control" id="cp_entreprise" name="cp_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="ville_entreprise" class="form-label">Ville</label>
                <input type="text" class="form-control" id="ville_entreprise" name="ville_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="tel_entreprise" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="tel_entreprise" name="tel_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="fax_entreprise" class="form-label">Fax</label>
                <input type="text" class="form-control" id="fax_entreprise" name="fax_entreprise">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="observations" class="form-label">Observations</label>
                <textarea class="form-control" id="observations" name="observations"></textarea>
            </div>
            <div class="mb-3">
                <label for="site_entreprise" class="form-label">Site web</label>
                <input type="text" class="form-control" id="site_entreprise" name="site_entreprise">
            </div>
            <div class="mb-3">
                <label for="niveau" class="form-label">Niveau</label>
                <input type="text" class="form-control" id="niveau" name="niveau" required>
            </div>
            <div class="mb-3">
                <label for="en_activite" class="form-label">En activité</label>
                <input type="checkbox" class="form-check-input" id="en_activite" name="en_activite">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Annuler</button>
        </form>
    `;
    formContainer.style.display = 'block';
}

function hideAddForm() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.style.display = 'none';
    formContainer.innerHTML = '';
}

function chercherEnt() {
    const formContainer = document.getElementById('search-form-container');
    formContainer.innerHTML = `
        <form id="search-form" action="?page=entreprise" method="post">
            <input type="hidden" name="page" value="entreprise">
            <div class="mb-3">
                <label for="search_criteria" class="form-label">Critère de recherche</label>
                <select class="form-select" id="search_criteria" name="search_criteria" onchange="updateSearchField()">
                    <option value="">Sélectionner un critère</option>
                    <option value="raison_sociale">Raison Sociale</option>
                    <option value="libelle">Spécialité</option>
                    <option value="nom_contact">Responsable</option>
                </select>
            </div>
            <div class="mb-3" id="search-field-container">
                <!-- Le champ de saisie sera inséré ici par JavaScript -->
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <button type="button" class="btn btn-secondary" onclick="hideSearchForm()">Annuler</button>
        </form>
    `;
    formContainer.style.display = 'block';
}

function updateSearchField() {
    const criteria = document.getElementById('search_criteria').value;
    const fieldContainer = document.getElementById('search-field-container');
    let fieldHtml = '';

    if (criteria === 'raison_sociale') {
        fieldHtml = `
            <label for="search_raison_sociale" class="form-label">Raison Sociale</label>
            <input type="text" class="form-control" id="search_raison_sociale" name="search_raison_sociale">
        `;
    } else if (criteria === 'libelle') {
        fieldHtml = `
            <label for="search_specialite" class="form-label">Spécialité</label>
            <input type="text" class="form-control" id="search_specialite" name="search_specialite">
        `;
    } else if (criteria === 'nom_contact') {
        fieldHtml = `
            <label for="search_nom_contact" class="form-label">Responsable</label>
            <input type="text" class="form-control" id="search_nom_contact" name="search_nom_contact">
        `;
    } else if (criteria === 'adresse') {
        fieldHtml = `
            <label for="search_adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="search_adresse" name="search_adresse">
        `;
    }

    fieldContainer.innerHTML = fieldHtml;
}

function hideSearchForm() {
    const formContainer = document.getElementById('search-form-container');
    formContainer.style.display = 'none';
    formContainer.innerHTML = '';
}

