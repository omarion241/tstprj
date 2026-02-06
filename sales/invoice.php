<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$id = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

// Charger la vente.
$saleSql = "SELECT id, client, is_counter_sale, created_at FROM sales WHERE id = '$id'";
$saleResult = mysqli_query($conn, $saleSql);
$sale = mysqli_fetch_assoc($saleResult);

// Charger les détails.
$detailSql = "SELECT si.quantity, si.unit_price, i.designation
              FROM sale_items si
              JOIN items i ON i.id = si.item_id
              WHERE si.sale_id = '$id'";
$details = mysqli_query($conn, $detailSql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Facture</h1>
    <?php if ($sale): ?>
        <p>Vente #<?php echo $sale['id']; ?></p>
        <p>Client: <?php echo htmlspecialchars($sale['client']); ?></p>
        <p>Vente comptoir: <?php echo $sale['is_counter_sale'] ? 'Oui' : 'Non'; ?></p>
        <p>Date: <?php echo $sale['created_at']; ?></p>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php while ($row = mysqli_fetch_assoc($details)): ?>
                    <?php $line = $row['quantity'] * $row['unit_price']; ?>
                    <?php $total += $line; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['designation']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                        <td><?php echo htmlspecialchars($line); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p>Total: <?php echo $total; ?></p>
    <?php else: ?>
        <p class="error">Vente introuvable.</p>
    <?php endif; ?>
    <p><a href="/workspace/tstprj/sales/sale_add.php">Retour</a></p>
</body>
</html>
