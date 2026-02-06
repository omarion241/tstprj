<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Récupérer l'ID.
$id = mysqli_real_escape_string($conn, $_GET['id'] ?? ''); // Nettoyer l'ID.

// Charger l'entrepôt.
$sql = "SELECT id, name, location FROM warehouses WHERE id = '$id'"; // SQL sélection.
$result = mysqli_query($conn, $sql); // Exécuter.
$warehouse = mysqli_fetch_assoc($result); // Récupérer.

// Initialiser les messages.
$message = '';
$error = '';

// Traiter la mise à jour.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');

    if ($name === '' || $location === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $updateSql = "UPDATE warehouses SET name = '$name', location = '$location' WHERE id = '$id'"; // SQL update.
        if (mysqli_query($conn, $updateSql)) {
            $message = 'Entrepôt modifié avec succès.';
        } else {
            $error = 'Erreur lors de la modification.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un entrepôt</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Modifier un entrepôt</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($warehouse): ?>
        <form method="POST" action="">
            <label>Nom</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($warehouse['name']); ?>" required>
            <label>Localisation</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($warehouse['location']); ?>" required>
            <button type="submit">Mettre à jour</button>
        </form>
    <?php else: ?>
        <p class="error">Entrepôt introuvable.</p>
    <?php endif; ?>
    <p><a href="/workspace/tstprj/warehouses/warehouse_list.php">Retour</a></p>
</body>
</html>
