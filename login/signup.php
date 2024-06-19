<?php
session_start();

// Vérifie si l'utilisateur est connecté et s'il est administrateur
function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
}

// Vérifie si le formulaire d'inscription est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    // Récupère les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'] ?? 'admin'; // Le rôle de l'utilisateur (admin, enseignant, eleve) avec 'admin' comme rôle par défaut

    // Mot de passe par défaut
    $default_password = '1234';

    // Insertion dans la base de données
    // Vous devez avoir une connexion à la base de données configurée (voir db.php dans votre code)
    // Remplacez les valeurs des champs et la requête SQL par les vôtres
    include '../admin/db.php'; // Incluez votre fichier de connexion à la base de données
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($default_password, PASSWORD_DEFAULT), // Hachage du mot de passe par défaut
        ':role' => $role
    ]);

    // Redirection vers une page de confirmation ou une autre page appropriée
    header("Location: admin/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur</title>
</head>
<body>
    <h2>Inscription Utilisateur</h2>
    <?php if (isAdmin()): ?>
    <form method="POST">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="role">Rôle:</label>
        <select id="role" name="role" required>
            <option value="admin" selected>Administrateur</option>
            <option value="enseignant">Enseignant</option>
            <option value="eleve">Elève</option>
        </select><br><br>
        <button type="submit">Enregistrer</button>
    </form>
    <?php else: ?>
    <p>Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
    <?php endif; ?>
</body>
</html>
