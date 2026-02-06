<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reference = mysqli_real_escape_string($conn, $_POST['reference'] ?? '');
    $designation = mysqli_real_escape_string($conn, $_POST['designation'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $purchase_price = mysqli_real_escape_string($conn, $_POST['purchase_price'] ?? '');
    $sale_price = mysqli_real_escape_string($conn, $_POST['sale_price'] ?? '');
    $min_stock = mysqli_real_escape_string($conn, $_POST['min_stock'] ?? '');
    $unit = mysqli_real_escape_string($conn, $_POST['unit'] ?? '');

    if ($reference === '' || $designation === '' || $category === '' || $purchase_price === '' || $sale_price === '' || $min_stock === '' || $unit === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $sql = "INSERT INTO items (reference, designation, category, purchase_price, sale_price, min_stock, unit)
                VALUES ('$reference', '$designation', '$category', '$purchase_price', '$sale_price', '$min_stock', '$unit')";
        if (mysqli_query($conn, $sql)) {
            $message = 'Article ajouté avec succès.';
        } else {
            $error = 'Erreur lors de l\'ajout.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Ajouter un article</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Référence</label>
        <input type="text" name="reference" required>
        <label>Désignation</label>
        <input type="text" name="designation" required>
        <label>Catégorie</label>
        <input type="text" name="category" required>
        <label>Prix achat</label>
        <input type="number" step="0.01" name="purchase_price" required>
        <label>Prix vente</label>
        <input type="number" step="0.01" name="sale_price" required>
        <label>Stock minimum</label>
        <input type="number" name="min_stock" required>
        <label>Unité</label>
        <input type="text" name="unit" required>
        <button type="submit">Enregistrer</button>
    </form>
    <p>Exemple: REF-002, Souris, Informatique, 40, 60, 10, pièce</p>
    <p><a href="/workspace/tstprj/items/item_list.php">Retour</a></p>
</body>
</html>
