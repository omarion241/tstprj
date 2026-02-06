<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Total ventes.
$salesSql = "SELECT SUM(quantity * unit_price) AS total_sales FROM sale_items";
$salesResult = mysqli_query($conn, $salesSql);
$salesRow = mysqli_fetch_assoc($salesResult);

// Total achats.
$purchaseSql = "SELECT SUM(quantity * unit_price) AS total_purchases FROM purchase_items";
$purchaseResult = mysqli_query($conn, $purchaseSql);
$purchaseRow = mysqli_fetch_assoc($purchaseResult);

$total_sales = $salesRow['total_sales'] ?? 0;
$total_purchases = $purchaseRow['total_purchases'] ?? 0;
$profit = $total_sales - $total_purchases;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bénéfice & perte</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Bénéfice & perte</h1>
    <p>Total ventes: <?php echo $total_sales; ?></p>
    <p>Total achats: <?php echo $total_purchases; ?></p>
    <p>Bénéfice / Perte: <?php echo $profit; ?></p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
