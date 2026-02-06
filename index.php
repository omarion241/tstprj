<?php
// Protéger la page.
require_once __DIR__ . '/auth/check_auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Stock - Accueil</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Gestion Stock</h1>
    <nav>
        <ul>
            <li><a href="/workspace/tstprj/warehouses/warehouse_list.php">Entrepôts</a></li>
            <li><a href="/workspace/tstprj/items/item_list.php">Articles</a></li>
            <li><a href="/workspace/tstprj/stock/stock_entry.php">Entrée Stock</a></li>
            <li><a href="/workspace/tstprj/stock/stock_exit.php">Sortie Stock</a></li>
            <li><a href="/workspace/tstprj/stock/stock_transfer.php">Transfert Stock</a></li>
            <li><a href="/workspace/tstprj/purchases/purchase_add.php">Achats</a></li>
            <li><a href="/workspace/tstprj/sales/sale_add.php">Ventes</a></li>
            <li><a href="/workspace/tstprj/payments/payment_add.php">Paiements</a></li>
            <li><a href="/workspace/tstprj/reports/report_stock.php">Rapports</a></li>
            <li><a href="/workspace/tstprj/auth/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</body>
</html>
