<?php
// Inclure la configuration et démarrer la session.
require_once __DIR__ . '/../config.php'; // Charger la connexion.

// Vérifier si l'utilisateur est connecté.
if (!isset($_SESSION['user_id'])) { // Si la session n'a pas d'utilisateur.
    header('Location: /workspace/tstprj/auth/login.php'); // Rediriger vers login.
    exit; // Stopper l'exécution.
}
?>
