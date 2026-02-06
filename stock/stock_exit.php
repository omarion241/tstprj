<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$warehouses = mysqli_query($conn, 'SELECT id, name FROM warehouses ORDER BY name');
$items = mysqli_query($conn, 'SELECT id, designation FROM items ORDER BY designation');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $warehouse_id = mysqli_real_escape_string($conn, $_POST['warehouse_id'] ?? '');
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id'] ?? '');
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity'] ?? '');
    $unit_price = mysqli_real_escape_string($conn, $_POST['unit_price'] ?? '');

    if ($warehouse_id === '' || $item_id === '' || $quantity === '' || $unit_price === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $movementSql = "INSERT INTO stock_movements (warehouse_id, item_id, quantity, unit_price, movement_type)
                        VALUES ('$warehouse_id', '$item_id', '$quantity', '$unit_price', 'OUT')";
        if (mysqli_query($conn, $movementSql)) {
            $checkSql = "SELECT id, quantity FROM stock_levels WHERE warehouse_id = '$warehouse_id' AND item_id = '$item_id'";
            $checkResult = mysqli_query($conn, $checkSql);
            $existing = mysqli_fetch_assoc($checkResult);

            if ($existing) {
                $newQty = $existing['quantity'] - $quantity;
                $updateSql = "UPDATE stock_levels SET quantity = '$newQty' WHERE id = '{$existing['id']}'";
                mysqli_query($conn, $updateSql);
            }
            $message = 'Sortie de stock enregistrée.';
        } else {
            $error = 'Erreur lors de l\'enregistrement.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sortie de stock</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Sortie de stock (Vente)</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Entrepôt</label>
        <select name="warehouse_id" required>
            <option value="">-- Choisir --</option>
            <?php while ($w = mysqli_fetch_assoc($warehouses)): ?>
                <option value="<?php echo $w['id']; ?>"><?php echo htmlspecialchars($w['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <label>Article</label>
        <select name="item_id" required>
            <option value="">-- Choisir --</option>
            <?php while ($i = mysqli_fetch_assoc($items)): ?>
                <option value="<?php echo $i['id']; ?>"><?php echo htmlspecialchars($i['designation']); ?></option>
            <?php endwhile; ?>
        </select>
        <label>Quantité</label>
        <input type="number" name="quantity" required>
        <label>Prix unitaire</label>
        <input type="number" step="0.01" name="unit_price" required>
        <button type="submit">Enregistrer</button>
    </form>
    <p>Exemple: Entrepôt = Dépôt Nord, Article = Clavier, Quantité = 5, Prix = 120</p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
