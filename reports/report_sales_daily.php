<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Requête ventes journalières.
$sql = "SELECT DATE(created_at) AS sale_date, SUM(si.quantity * si.unit_price) AS total_sales
        FROM sales s
        JOIN sale_items si ON si.sale_id = s.id
        GROUP BY DATE(created_at)
        ORDER BY sale_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ventes journalières</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Ventes journalières</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total ventes</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['sale_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_sales']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
