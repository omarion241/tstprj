<?php
// Inclure la configuration pour la connexion DB.
require_once __DIR__ . '/../config.php';

// Initialiser le message d'erreur.
$error = ''; // Message d'erreur vide.

// Traiter le formulaire si soumis.
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Vérifier la méthode HTTP.
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? ''); // Nettoyer l'email.
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? ''); // Nettoyer le mot de passe.

    // Valider les champs.
    if ($email === '' || $password === '') { // Si un champ est vide.
        $error = 'Veuillez remplir tous les champs.'; // Définir l'erreur.
    } else {
        // Requête pour récupérer l'utilisateur.
        $sql = "SELECT id, password_hash FROM users WHERE email = '$email'"; // SQL login.
        $result = mysqli_query($conn, $sql); // Exécuter la requête.
        $user = mysqli_fetch_assoc($result); // Récupérer la ligne.

        // Vérifier le mot de passe.
        if ($user && password_verify($password, $user['password_hash'])) { // Si mot de passe correct.
            $_SESSION['user_id'] = $user['id']; // Enregistrer l'utilisateur.
            header('Location: /workspace/tstprj/index.php'); // Rediriger.
            exit; // Stopper.
        } else {
            $error = 'Identifiants invalides.'; // Définir l'erreur.
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="/workspace/tstprj/assets/style.css">
</head>
<body>
    <h1>Connexion</h1>
    <?php if ($error !== ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Mot de passe</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Exemple: admin@demo.com / admin123</p>
</body>
</html>
