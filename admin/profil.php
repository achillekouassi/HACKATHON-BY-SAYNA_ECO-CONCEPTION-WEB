<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include 'db.php';

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];


// Récupérer les informations de l'utilisateur depuis la base de données
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$userData = $stmt->fetch();


?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                <div class="avatars "><?php echo $initials; ?></div>
                    <h5 class="my-3"><?php echo htmlspecialchars($userData['role']); ?></h5>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($userData['username']); ?> <?php echo htmlspecialchars($userData['prenom']); ?></p>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($userData['telephone']); ?></p>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="update_profile.php">
                        <div class="row mb-3">
                            <label for="username" class="col-sm-3 col-form-label">Nom d'utilisateur</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userData['username']); ?>" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userData['email']); ?>" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-sm-3 col-form-label">Mot de passe</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Modifier le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>

?>
