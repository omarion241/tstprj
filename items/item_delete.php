<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$id = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

$sql = "DELETE FROM items WHERE id = '$id'";
mysqli_query($conn, $sql);

header('Location: /workspace/tstprj/items/item_list.php');
exit;
?>
