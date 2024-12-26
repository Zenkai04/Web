document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/projets/Web/src/Controller/Inscription.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Change to text() to see the raw response
    .then(text => {
        console.log('Raw response:', text); // Log the raw response
        return JSON.parse(text); // Parse the text as JSON
    })
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