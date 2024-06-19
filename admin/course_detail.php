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

// Vérifier si l'ID du cours est fourni dans l'URL
if (!isset($_GET['course_id'])) {
    // Rediriger vers une page d'erreur ou à la liste des cours
    header("Location: error.php");
    exit;
}

$courseId = $_GET['course_id'];

// Récupérer les détails du cours depuis la base de données
$sql = "SELECT cc.*, c.name AS course_name 
        FROM course_contents cc
        JOIN courses c ON cc.course_id = c.id
        WHERE c.id = ?";
     
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    // Rediriger vers une page d'erreur ou à la liste des cours
    header("Location: error.php");
    exit;
}

// Assurez-vous que l'utilisateur a accès à ce cours (vous pouvez implémenter votre propre logique ici)

// Afficher les informations du module
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Contenu du Cours : <?= htmlspecialchars($course['course_name']) ?></h1>

    <div class="course-content">
        <h3><?= htmlspecialchars($course['title']) ?></h3>
        <p><?= htmlspecialchars_decode($course['body']) ?></p>
    </div>

    <!-- Bouton pour voir les évaluations -->
    <div class="mt-3">
        <a href="evaluations.php?course_id=<?= $courseId ?>" class="btn btn-primary">Voir mes évaluations</a>
    </div>

</div>

<?php include 'layout/footer.php'; ?>
