function confirmDeleteEtu(numEtudiant) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce stagiaire ?')) {
        document.getElementById('delete-form-' + numEtudiant).submit();
    }
}

function confirmDeleteEnt(numEntreprise) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
        document.getElementById('delete-form-' + numEntreprise).submit();
    }
}

function ajoutEtu() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.innerHTML = `
        <form id="add-stagiaire-form" action="?page=stagiaire" method="post">
            <input type="hidden" name="action" value="add">
            
            <h3>Information</h3>
            <div class="mb-3">
                <label for="nom_etudiant" class="form-label">Nom*</label>
                <input type="text" class="form-control" id="nom_etudiant" name="nom_etudiant" required>
            </div>
            <div class="mb-3">
                <label for="prenom_etudiant" class="form-label">Prénom*</label>
                <input type="text" class="form-control" id="prenom_etudiant" name="prenom_etudiant" required>
            </div>
            <div class="mb-3">
                <label for="login" class="form-label">Login*</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe*</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">Afficher</button>
            </div>
            <div class="mb-3">
                <label for="annee_obtention" class="form-label">Année d'obtention</label>
                <input type="text" class="form-control" id="annee_obtention" name="annee_obtention">
            </div>
            
            <h3>Classe</h3>
            <div class="mb-3">
                <label for="num_classe" class="form-label">Classe*</label>
                <select class="form-control" id="num_classe" name="num_classe" required>
                    ${classes.map(classe => `<option value="${classe.num_classe}">${classe.nom_classe}</option>`).join('')}
                </select>
            </div>
            
            <div class="mb-3">
                <label for="en_activite" class="form-label">En activité</label>
                <input type="checkbox" class="form-check-input" id="en_activite" name="en_activite">
            </div>

            <div class="mb-3" style="text-align: center; background-color: #ff6161; color: white; padding: 10px;">
                <p>Les champs désignés par un * sont obligatoires</p>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Annuler</button>
        </form>

        
    `;
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('mdp');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Afficher' : 'Masquer';
    });

    formContainer.style.display = 'block';
}

function ajoutEnt() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.innerHTML = `
        <form id="add-enterprise-form" action="?page=entreprise" method="post">
            <input type="hidden" name="action" value="add">
            
            <h3>Information</h3>
            <div class="mb-3">
                <label for="raison_sociale" class="form-label">Nom de l'entreprise*</label>
                <input type="text" class="form-control" id="raison_sociale" name="raison_sociale" required>
            </div>
            <div class="mb-3">
                <label for="nom_contact" class="form-label">Nom du contact*</label>
                <input type="text" class="form-control" id="nom_contact" name="nom_contact" required>
            </div>
            <div class="mb-3">
                <label for="nom_resp" class="form-label">Nom du responsable</label>
                <input type="text" class="form-control" id="nom_resp" name="nom_resp">
            </div>
            
            <h3>Contact</h3>
            <div class="mb-3">
                <label for="rue_entreprise" class="form-label">Rue*</label>
                <input type="text" class="form-control" id="rue_entreprise" name="rue_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="cp_entreprise" class="form-label">Code postal*</label>
                <input type="text" class="form-control" id="cp_entreprise" name="cp_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="ville_entreprise" class="form-label">Ville*</label>
                <input type="text" class="form-control" id="ville_entreprise" name="ville_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="tel_entreprise" class="form-label">Téléphone*</label>
                <input type="text" class="form-control" id="tel_entreprise" name="tel_entreprise" required>
            </div>
            <div class="mb-3">
                <label for="fax_entreprise" class="form-label">Fax</label>
                <input type="text" class="form-control" id="fax_entreprise" name="fax_entreprise">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <h3>Divers</h3>
            <div class="mb-3">
                <label for="observation" class="form-label">Observation</label>
                <textarea class="form-control" id="observation" name="observation"></textarea>
            </div>
            <div class="mb-3">
                <label for="site_entreprise" class="form-label">Site Web</label>
                <input type="url" class="form-control" id="site_entreprise" name="site_entreprise">
            </div>
            <div class="mb-3">
                <label for="niveau" class="form-label">Niveau*</label>
                <input type="text" class="form-control" id="niveau" name="niveau" required>
            </div>
            
            <h3>Spécialité</h3>
            <div class="mb-3">
                <label for="specialite" class="form-label">Spécialité*</label>
                <select class="form-control" id="specialite" name="specialite" required>
                    ${specialites.map(specialite => `<option value="${specialite.num_spec}">${specialite.libelle}</option>`).join('')}
                </select>
            </div>

            <div class="mb-3" style="text-align: center; background-color: #ff6161; color: white; padding: 10px;">
                <p>Les champs désignés par un * sont obligatoires</p>
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
}

function chercherEnt() {
    const formContainer = document.getElementById('search-form-container');
    formContainer.innerHTML = `
        <form id="search-form" action="?page=entreprise" method="post">
            <input type="hidden" name="action" value="search">
            <div class="mb-3">
                <label for="search_criteria" class="form-label">Critère de recherche</label>
                <select class="form-select" id="search_criteria" name="search_criteria" onchange="updateSearchFieldEnt()">
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

function updateSearchFieldEnt() {
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
            <label for="search_libelle" class="form-label">Spécialité</label>
            <input type="text" class="form-control" id="search_libelle" name="search_libelle">
        `;
    } else if (criteria === 'nom_contact') {
        fieldHtml = `
            <label for="search_nom_contact" class="form-label">Responsable</label>
            <input type="text" class="form-control" id="search_nom_contact" name="search_nom_contact">
        `;
    }

    fieldContainer.innerHTML = fieldHtml;
}

function chercherEtu() {
    const formContainer = document.getElementById('search-form-container');
    formContainer.innerHTML = `
        <form id="search-form" action="?page=stagiaire" method="post">
            <input type="hidden" name="action" value="search">
            <div class="mb-3">
                <label for="search_criteria" class="form-label">Critère de recherche</label>
                <select class="form-select" id="search_criteria" name="search_criteria" onchange="updateSearchFieldEtu()">
                    <option value="">Sélectionner un critère</option>
                    <option value="nom_etudiant">Nom</option>
                    <option value="prenom_etudiant">Prénom</option>
                    <option value="nom_prof">Professeur</option>
                    <option value="raison_sociale">Entreprise</option>
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

function updateSearchFieldEtu() {
    const criteria = document.getElementById('search_criteria').value;
    const fieldContainer = document.getElementById('search-field-container');
    let fieldHtml = '';

    if (criteria === 'nom_etudiant') {
        fieldHtml = `
            <label for="search_nom_etudiant" class="form-label">Nom</label>
            <input type="text" class="form-control" id="search_nom_etudiant" name="search_nom_etudiant">
        `;
    } else if (criteria === 'prenom_etudiant') {
        fieldHtml = `
            <label for="search_prenom_etudiant" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="search_prenom_etudiant" name="search_prenom_etudiant">
        `;
    } else if (criteria === 'nom_prof') {
        fieldHtml = `
            <label for="search_nom_prof" class="form-label">Nom du professeur</label>
            <input type="text" class="form-control" id="search_nom_prof" name="search_nom_prof">
        `;
    } else if (criteria === 'raison_sociale') {
        fieldHtml = `
            <label for="search_raison_sociale" class="form-label">Raison Sociale</label>
            <input type="text" class="form-control" id="search_raison_sociale" name="search_raison_sociale">
        `;
    }

    fieldContainer.innerHTML = fieldHtml;
}

function hideSearchForm() {
    document.getElementById('search-form-container').style.display = 'none';
}

function hideSuccessMessage() {
    document.getElementById('success-message').style.display = 'none';
}

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('mdp');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Afficher' : 'Masquer';
});
