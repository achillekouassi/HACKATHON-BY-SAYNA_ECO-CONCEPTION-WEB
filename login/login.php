<?php 
session_start();
include '../admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->execute([':email' => $email, ':password' => md5($password)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['status'] === 'active') {
                // Stockage des données de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['prenom'] = $user['prenom'];

                // Redirection vers la page d'administration ou d'accueil en fonction du rôle
                if ($user['role'] === 'student') {
                    header("Location: ../admin/horaire_etudiant.php");
                } else if ($user['role'] === 'admin'){
                    header("Location: ../admin/index.php");
                }
                else {
                    header("Location: ../admin/enseignant.php");
                }
                exit;
            } else {
                echo "Votre compte est inactif. Veuillez contacter votre administrateur.";
            }
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form  method="POST">
                <img src="../images/eranove-1-300x117.png" alt="" width="50%">
                <h2>Connexion</h2>
                <div class="social-icons"></div>
                <input type="email" placeholder="Email" name="email">
                <input type="password" placeholder="Mot de passe" name="password">
                <input type="submit" class="btn btn-primary" value="Connexion">
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Espace utilisateur</h1>
                    <a href="../index.php" class="hidden" id="register">retour</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
