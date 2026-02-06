<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Initialiser les messages.
$message = ''; // Message de succès.
$error = ''; // Message d'erreur.

// Traiter le formulaire.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? ''); // Nettoyer le nom.
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? ''); // Nettoyer la localisation.

    if ($name === '' || $location === '') {
        $error = 'Tous les champs sont obligatoires.'; // Définir l'erreur.
    } else {
        // Requête d'insertion.
        $sql = "INSERT INTO warehouses (name, location) VALUES ('$name', '$location')"; // SQL insertion.
        if (mysqli_query($conn, $sql)) {
            $message = 'Entrepôt ajouté avec succès.'; // Définir le message.
        } else {
            $error = 'Erreur lors de l\'ajout.'; // Définir l'erreur.
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un entrepôt</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Ajouter un entrepôt</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Nom</label>
        <input type="text" name="name" required>
        <label>Localisation</label>
        <input type="text" name="location" required>
        <button type="submit">Enregistrer</button>
    </form>
    <p>Exemple: Nom = Dépôt Sud, Localisation = Rabat</p>
    <p><a href="/workspace/tstprj/warehouses/warehouse_list.php">Retour</a></p>
</body>
</html>
