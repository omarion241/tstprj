<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

// Récupérer l'ID.
$id = mysqli_real_escape_string($conn, $_GET['id'] ?? ''); // Nettoyer l'ID.

// Supprimer l'entrepôt.
$sql = "DELETE FROM warehouses WHERE id = '$id'"; // SQL suppression.
mysqli_query($conn, $sql); // Exécuter la suppression.

// Rediriger vers la liste.
header('Location: /workspace/tstprj/warehouses/warehouse_list.php');
exit;
?>
