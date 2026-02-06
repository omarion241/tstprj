<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$start_date = mysqli_real_escape_string($conn, $_GET['start_date'] ?? '');
$end_date = mysqli_real_escape_string($conn, $_GET['end_date'] ?? '');

$sql = "SELECT p.id, p.supplier, p.bl_number, p.payment_status, p.created_at,
        SUM(pi.quantity * pi.unit_price) AS total
        FROM purchases p
        JOIN purchase_items pi ON pi.purchase_id = p.id
        WHERE 1=1";

if ($start_date !== '') {
    $sql .= " AND DATE(p.created_at) >= '$start_date'";
}
if ($end_date !== '') {
    $sql .= " AND DATE(p.created_at) <= '$end_date'";
}

$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Achats par période</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Achats par période</h1>
    <form method="GET" action="">
        <label>Date début</label>
        <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
        <label>Date fin</label>
        <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
        <button type="submit">Filtrer</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fournisseur</th>
                <th>BL</th>
                <th>Statut</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                    <td><?php echo htmlspecialchars($row['bl_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['total']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
