<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Requête stock par entrepôt.
$sql = "SELECT w.name AS warehouse, i.designation, s.quantity, i.min_stock
        FROM stock_levels s
        JOIN warehouses w ON w.id = s.warehouse_id
        JOIN items i ON i.id = s.item_id
        ORDER BY w.name, i.designation";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport stock</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Stock par entrepôt</h1>
    <table>
        <thead>
            <tr>
                <th>Entrepôt</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Stock minimum</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['warehouse']); ?></td>
                    <td><?php echo htmlspecialchars($row['designation']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['min_stock']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="/workspace/tstprj/reports/report_stock_alerts.php">Alertes stock minimum</a></p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
