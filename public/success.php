<?php
// Assurez-vous de définir l'en-tête Content-Type pour JSON
header('Content-Type: application/json');

// Logique de traitement de l'inscription (ici, on simule un succès)
$success = true; // Changez ceci en fonction du résultat de votre logique d'inscription

// Réponse en JSON
if ($success) {
    echo json_encode([
        'success' => true,
        'message' => 'Inscription réussie'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue lors de l\'inscription.'
    ]);
}

// Terminer le script
exit;
?>
