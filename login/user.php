<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé : vous n'avez pas les permissions nécessaires.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':email' => $email,
            ':role' => $role
        ]);
        echo "Utilisateur inscrit avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>
