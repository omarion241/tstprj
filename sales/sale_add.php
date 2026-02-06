<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$items = mysqli_query($conn, 'SELECT id, designation FROM items ORDER BY designation');
$warehouses = mysqli_query($conn, 'SELECT id, name FROM warehouses ORDER BY name');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = mysqli_real_escape_string($conn, $_POST['client'] ?? '');
    $is_counter_sale = mysqli_real_escape_string($conn, $_POST['is_counter_sale'] ?? '');
    $warehouse_id = mysqli_real_escape_string($conn, $_POST['warehouse_id'] ?? '');
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id'] ?? '');
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity'] ?? '');
    $unit_price = mysqli_real_escape_string($conn, $_POST['unit_price'] ?? '');

    if ($warehouse_id === '' || $item_id === '' || $quantity === '' || $unit_price === '') {
        $error = 'Les champs obligatoires doivent être remplis.';
    } else {
        // Créer la vente.
        $saleSql = "INSERT INTO sales (client, is_counter_sale)
                    VALUES ('$client', '$is_counter_sale')";
        if (mysqli_query($conn, $saleSql)) {
            $sale_id = mysqli_insert_id($conn);
            // Insérer détail vente.
            $detailSql = "INSERT INTO sale_items (sale_id, item_id, quantity, unit_price)
                          VALUES ('$sale_id', '$item_id', '$quantity', '$unit_price')";
            mysqli_query($conn, $detailSql);

            // Mouvement stock.
            $movementSql = "INSERT INTO stock_movements (warehouse_id, item_id, quantity, unit_price, movement_type, ref_id)
                            VALUES ('$warehouse_id', '$item_id', '$quantity', '$unit_price', 'OUT', '$sale_id')";
            mysqli_query($conn, $movementSql);

            // Mettre à jour stock.
            $checkSql = "SELECT id, quantity FROM stock_levels WHERE warehouse_id = '$warehouse_id' AND item_id = '$item_id'";
            $checkResult = mysqli_query($conn, $checkSql);
            $existing = mysqli_fetch_assoc($checkResult);
            if ($existing) {
                $newQty = $existing['quantity'] - $quantity;
                mysqli_query($conn, "UPDATE stock_levels SET quantity = '$newQty' WHERE id = '{$existing['id']}'");
            }

            $message = 'Vente enregistrée. <a href="/workspace/tstprj/sales/invoice.php?id=' . $sale_id . '">Voir facture</a>';
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
    <title>Enregistrer une vente</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Ventes</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Client (optionnel)</label>
        <input type="text" name="client">
        <label>Vente comptoir</label>
        <select name="is_counter_sale">
            <option value="1">Oui</option>
            <option value="0">Non</option>
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
    <p>Exemple: Client = SARL Alpha, Vente comptoir = Non, Article = Souris, Quantité = 2</p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
