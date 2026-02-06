<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$items = mysqli_query($conn, 'SELECT id, designation FROM items ORDER BY designation');
$warehouses = mysqli_query($conn, 'SELECT id, name FROM warehouses ORDER BY name');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier'] ?? '');
    $bl_number = mysqli_real_escape_string($conn, $_POST['bl_number'] ?? '');
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status'] ?? '');
    $warehouse_id = mysqli_real_escape_string($conn, $_POST['warehouse_id'] ?? '');
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id'] ?? '');
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity'] ?? '');
    $unit_price = mysqli_real_escape_string($conn, $_POST['unit_price'] ?? '');

    if ($supplier === '' || $bl_number === '' || $payment_status === '' || $warehouse_id === '' || $item_id === '' || $quantity === '' || $unit_price === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        // Créer l'achat.
        $purchaseSql = "INSERT INTO purchases (supplier, bl_number, payment_status)
                        VALUES ('$supplier', '$bl_number', '$payment_status')";
        if (mysqli_query($conn, $purchaseSql)) {
            $purchase_id = mysqli_insert_id($conn); // Récupérer l'ID achat.
            // Insérer le détail achat.
            $detailSql = "INSERT INTO purchase_items (purchase_id, item_id, quantity, unit_price)
                          VALUES ('$purchase_id', '$item_id', '$quantity', '$unit_price')";
            mysqli_query($conn, $detailSql);

            // Mouvement de stock.
            $movementSql = "INSERT INTO stock_movements (warehouse_id, item_id, quantity, unit_price, movement_type, ref_id)
                            VALUES ('$warehouse_id', '$item_id', '$quantity', '$unit_price', 'IN', '$purchase_id')";
            mysqli_query($conn, $movementSql);

            // Mettre à jour stock.
            $checkSql = "SELECT id, quantity FROM stock_levels WHERE warehouse_id = '$warehouse_id' AND item_id = '$item_id'";
            $checkResult = mysqli_query($conn, $checkSql);
            $existing = mysqli_fetch_assoc($checkResult);
            if ($existing) {
                $newQty = $existing['quantity'] + $quantity;
                mysqli_query($conn, "UPDATE stock_levels SET quantity = '$newQty' WHERE id = '{$existing['id']}'");
            } else {
                mysqli_query($conn, "INSERT INTO stock_levels (warehouse_id, item_id, quantity)
                                      VALUES ('$warehouse_id', '$item_id', '$quantity')");
            }

            $message = 'Achat enregistré.';
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
    <title>Enregistrer un achat</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Achats</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Fournisseur</label>
        <input type="text" name="supplier" required>
        <label>BL (bon de livraison)</label>
        <input type="text" name="bl_number" required>
        <label>Statut paiement</label>
        <select name="payment_status" required>
            <option value="payé">Payé</option>
            <option value="à terme">À terme</option>
        </select>
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
    <p>Exemple: Fournisseur = Tech Supplier, BL = BL-2024-01, Statut = payé, Article = Clavier, Quantité = 10</p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
