<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Exemple de données: INSERT INTO items (reference, designation, category, purchase_price, sale_price, min_stock, unit)
// VALUES ('REF-001', 'Clavier', 'Informatique', 80, 120, 5, 'pièce');

// Requête pour lister les articles.
$sql = 'SELECT id, reference, designation, category, purchase_price, sale_price, min_stock, unit FROM items ORDER BY designation';
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des articles</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Articles</h1>
    <a href="/workspace/tstprj/items/item_add.php">Ajouter un article</a>
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Catégorie</th>
                <th>Prix Achat</th>
                <th>Prix Vente</th>
                <th>Stock Min</th>
                <th>Unité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['reference']); ?></td>
                    <td><?php echo htmlspecialchars($row['designation']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['purchase_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['sale_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['min_stock']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td>
                        <a href="/workspace/tstprj/items/item_edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="/workspace/tstprj/items/item_delete.php?id=<?php echo $row['id']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
