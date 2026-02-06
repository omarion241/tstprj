<?php
// Démarrer la session pour l'authentification.
session_start();

// Définir les paramètres de connexion MySQL.
$DB_HOST = 'localhost'; // Hôte MySQL.
$DB_USER = 'root'; // Utilisateur MySQL.
$DB_PASS = ''; // Mot de passe MySQL.
$DB_NAME = 'gestion_stock'; // Nom de la base de données.

// Créer la connexion MySQL en procédural.
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME); // Connexion mysqli.

// Vérifier la connexion.
if (!$conn) { // Si la connexion échoue.
    die('Erreur de connexion : ' . mysqli_connect_error()); // Afficher l'erreur et arrêter.
}

// Forcer l'encodage UTF-8.
mysqli_set_charset($conn, 'utf8'); // Définir l'encodage.
?>
