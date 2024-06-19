<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: ../login/login.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../admin/db.php';

// Récupérer l'ID de la classe
$classId = isset($_GET['class_id']) ? $_GET['class_id'] : null;

if (!$classId) {
    // Rediriger si aucun class_id n'est fourni
    header("Location: information.php");
    exit;
}

// Récupérer les informations de la classe
$sql = "SELECT * FROM classes WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$classId]);
$class = $stmt->fetch();

if (!$class) {
    // Rediriger si la classe n'existe pas
    header("Location: information.php");
    exit;
}

// Récupérer les étudiants associés à cette classe
$sql = "SELECT u.id, u.username, u.prenom 
        FROM user_assignments ua 
        JOIN users u ON ua.student_id = u.id 
        WHERE ua.class_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$classId]);
$students = $stmt->fetchAll();
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <h1>Classe: <?= htmlspecialchars($class['name']) ?></h1>
    <p><?= htmlspecialchars($class['description']) ?></p>
    <hr>
    <h2>Étudiants</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Prénom</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['username']) ?></td>
                        <td><?= htmlspecialchars($student['prenom']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php include 'layout/footer.php'; ?>
