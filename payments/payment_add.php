<?php
// Protéger la page.
require_once __DIR__ . '/../auth/check_auth.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $party_type = mysqli_real_escape_string($conn, $_POST['party_type'] ?? '');
    $party_name = mysqli_real_escape_string($conn, $_POST['party_name'] ?? '');
    $amount = mysqli_real_escape_string($conn, $_POST['amount'] ?? '');
    $modes = $_POST['payment_modes'] ?? []; // Modes multiples.
    $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');

    $modes_clean = array_map(function ($mode) use ($conn) {
        return mysqli_real_escape_string($conn, $mode); // Nettoyer chaque mode.
    }, $modes);
    $modes_string = implode(',', $modes_clean); // Combiner les modes.

    if ($party_type === '' || $party_name === '' || $amount === '' || $modes_string === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $sql = "INSERT INTO payments (party_type, party_name, amount, payment_modes, note)
                VALUES ('$party_type', '$party_name', '$amount', '$modes_string', '$note')";
        if (mysqli_query($conn, $sql)) {
            $message = 'Paiement enregistré.';
        } else {
            $error = 'Erreur lors de l\'enregistrement.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiements</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Paiements</h1>
    <?php if ($message !== ''): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Type (client/fournisseur)</label>
        <select name="party_type" required>
            <option value="client">Client</option>
            <option value="fournisseur">Fournisseur</option>
        </select>
        <label>Nom du client/fournisseur</label>
        <input type="text" name="party_name" required>
        <label>Montant payé</label>
        <input type="number" step="0.01" name="amount" required>
        <label>Modes de paiement (choix multiple)</label>
        <label><input type="checkbox" name="payment_modes[]" value="Espèces"> Espèces</label>
        <label><input type="checkbox" name="payment_modes[]" value="Virement"> Virement</label>
        <label><input type="checkbox" name="payment_modes[]" value="Chèque"> Chèque</label>
        <label><input type="checkbox" name="payment_modes[]" value="Dette"> Dette</label>
        <label>Note / suivi dette</label>
        <input type="text" name="note">
        <button type="submit">Enregistrer</button>
    </form>
    <p>Exemple: Client = SARL Alpha, Montant = 500, Modes = Espèces + Dette</p>
    <p><a href="/workspace/tstprj/index.php">Retour</a></p>
</body>
</html>
