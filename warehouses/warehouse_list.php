<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Exemple de données: INSERT INTO warehouses (name, location) VALUES ('Dépôt Nord', 'Casablanca');

// Requête pour lister les entrepôts.
$sql = 'SELECT id, name, location FROM warehouses ORDER BY name';
$result = mysqli_query($conn, $sql); // Exécuter la requête.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des entrepôts</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Entrepôts</h1>
    <a href="/workspace/tstprj/warehouses/warehouse_add.php">Ajouter un entrepôt</a>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Localisation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td>
                        <a href="/workspace/tstprj/warehouses/warehouse_edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="/workspace/tstprj/warehouses/warehouse_delete.php?id=<?php echo $row['id']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
