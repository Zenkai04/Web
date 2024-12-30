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

function hideAddForm() {
    const formContainer = document.getElementById('add-form-container');
    formContainer.style.display = 'none';
    formContainer.innerHTML = '';
}