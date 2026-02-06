<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$id = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

$sql = "SELECT * FROM items WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($result);

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
        $updateSql = "UPDATE items SET reference = '$reference', designation = '$designation', category = '$category',
                      purchase_price = '$purchase_price', sale_price = '$sale_price', min_stock = '$min_stock', unit = '$unit'
                      WHERE id = '$id'";
        if (mysqli_query($conn, $updateSql)) {
            $message = 'Article modifié avec succès.';
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
    <title>Modifier un article</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Modifier un article</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($item): ?>
        <form method="POST" action="">
            <label>Référence</label>
            <input type="text" name="reference" value="<?php echo htmlspecialchars($item['reference']); ?>" required>
            <label>Désignation</label>
            <input type="text" name="designation" value="<?php echo htmlspecialchars($item['designation']); ?>" required>
            <label>Catégorie</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required>
            <label>Prix achat</label>
            <input type="number" step="0.01" name="purchase_price" value="<?php echo htmlspecialchars($item['purchase_price']); ?>" required>
            <label>Prix vente</label>
            <input type="number" step="0.01" name="sale_price" value="<?php echo htmlspecialchars($item['sale_price']); ?>" required>
            <label>Stock minimum</label>
            <input type="number" name="min_stock" value="<?php echo htmlspecialchars($item['min_stock']); ?>" required>
            <label>Unité</label>
            <input type="text" name="unit" value="<?php echo htmlspecialchars($item['unit']); ?>" required>
            <button type="submit">Mettre à jour</button>
        </form>
    <?php else: ?>
        <p class="error">Article introuvable.</p>
    <?php endif; ?>
    <p><a href="/workspace/tstprj/items/item_list.php">Retour</a></p>
</body>
</html>
