<?php
// Inclure la configuration et démarrer la session.
require_once __DIR__ . '/../config.php';

// Détruire la session.
session_destroy(); // Supprimer la session.

// Rediriger vers login.
header('Location: /workspace/tstprj/auth/login.php');
exit;
?>
