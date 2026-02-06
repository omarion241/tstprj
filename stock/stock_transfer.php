<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$warehouses = mysqli_query($conn, 'SELECT id, name FROM warehouses ORDER BY name');
$items = mysqli_query($conn, 'SELECT id, designation FROM items ORDER BY designation');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_warehouse = mysqli_real_escape_string($conn, $_POST['from_warehouse'] ?? '');
    $to_warehouse = mysqli_real_escape_string($conn, $_POST['to_warehouse'] ?? '');
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id'] ?? '');
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity'] ?? '');

    if ($from_warehouse === '' || $to_warehouse === '' || $item_id === '' || $quantity === '') {
        $error = 'Tous les champs sont obligatoires.';
    } elseif ($from_warehouse === $to_warehouse) {
        $error = 'Les entrepôts doivent être différents.';
    } else {
        // Mouvement sortie.
        $outSql = "INSERT INTO stock_movements (warehouse_id, item_id, quantity, unit_price, movement_type)
                   VALUES ('$from_warehouse', '$item_id', '$quantity', 0, 'TRANSFER_OUT')";
        // Mouvement entrée.
        $inSql = "INSERT INTO stock_movements (warehouse_id, item_id, quantity, unit_price, movement_type)
                  VALUES ('$to_warehouse', '$item_id', '$quantity', 0, 'TRANSFER_IN')";

        if (mysqli_query($conn, $outSql) && mysqli_query($conn, $inSql)) {
            // Mettre à jour stock source.
            $checkFrom = "SELECT id, quantity FROM stock_levels WHERE warehouse_id = '$from_warehouse' AND item_id = '$item_id'";
            $fromResult = mysqli_query($conn, $checkFrom);
            $fromRow = mysqli_fetch_assoc($fromResult);
            if ($fromRow) {
                $newQtyFrom = $fromRow['quantity'] - $quantity;
                mysqli_query($conn, "UPDATE stock_levels SET quantity = '$newQtyFrom' WHERE id = '{$fromRow['id']}'");
            }

            // Mettre à jour stock destination.
            $checkTo = "SELECT id, quantity FROM stock_levels WHERE warehouse_id = '$to_warehouse' AND item_id = '$item_id'";
            $toResult = mysqli_query($conn, $checkTo);
            $toRow = mysqli_fetch_assoc($toResult);
            if ($toRow) {
                $newQtyTo = $toRow['quantity'] + $quantity;
                mysqli_query($conn, "UPDATE stock_levels SET quantity = '$newQtyTo' WHERE id = '{$toRow['id']}'");
            } else {
                mysqli_query($conn, "INSERT INTO stock_levels (warehouse_id, item_id, quantity)
                                      VALUES ('$to_warehouse', '$item_id', '$quantity')");
            }
            $message = 'Transfert enregistré.';
        } else {
            $error = 'Erreur lors du transfert.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transfert de stock</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Transfert entre entrepôts</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Entrepôt source</label>
        <select name="from_warehouse" required>
            <option value="">-- Choisir --</option>
            <?php mysqli_data_seek($warehouses, 0); ?>
            <?php while ($w = mysqli_fetch_assoc($warehouses)): ?>
                <option value="<?php echo $w['id']; ?>"><?php echo htmlspecialchars($w['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <label>Entrepôt destination</label>
        <select name="to_warehouse" required>
            <option value="">-- Choisir --</option>
            <?php mysqli_data_seek($warehouses, 0); ?>
            <?php while ($w2 = mysqli_fetch_assoc($warehouses)): ?>
                <option value="<?php echo $w2['id']; ?>"><?php echo htmlspecialchars($w2['name']); ?></option>
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
        <button type="submit">Transférer</button>
    </form>
    <p>Exemple: Source = Dépôt Nord, Destination = Dépôt Sud, Article = Clavier, Quantité = 3</p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
