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

// Récupérer les paramètres de l'URL
if (!isset($_GET['evaluation_id']) || !isset($_GET['course_id'])) {
    // Rediriger vers une page d'erreur ou à la liste des cours
    echo "Paramètres manquants dans l'URL"; // Message temporaire pour déboguer
    exit;
}

$evaluationId = $_GET['evaluation_id'];
$courseId = $_GET['course_id'];
$userId = $_SESSION['user_id'];

// Récupérer les détails de l'évaluation
$sqlEvaluation = "SELECT e.*, er.grade
                  FROM evaluations e
                  INNER JOIN evaluation_results er ON e.id = er.evaluation_id
                  WHERE e.id = ? AND er.student_id = ?";
$stmtEvaluation = $pdo->prepare($sqlEvaluation);
$stmtEvaluation->execute([$evaluationId, $userId]);
$evaluation = $stmtEvaluation->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'évaluation existe pour cet étudiant
if (!$evaluation) {
    // Rediriger ou afficher un message si l'évaluation n'existe pas
    echo "Vous n'avez pas de résultats pour cette évaluation.";
    exit;
}

// Récupérer les détails du cours pour l'affichage
$sqlCourse = "SELECT name, description FROM courses WHERE id = ?";
$stmtCourse = $pdo->prepare($sqlCourse);
$stmtCourse->execute([$courseId]);
$course = $stmtCourse->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Résultats de l'évaluation</h1>
    <h3>Cours : <?= htmlspecialchars($course['name']) ?></h3>
    <p><?= htmlspecialchars($course['description']) ?></p>

    <h4>Évaluation : <?= htmlspecialchars($evaluation['question']) ?></h4>
    <p>Type : <?= ucfirst($evaluation['type']) ?></p>
    <p>Date limite : <?= $evaluation['available_until'] ?></p>

    <h4>Votre Grade : <?= $evaluation['grade'] ?></h4>

    <!-- Afficher d'autres détails de l'évaluation selon le type (par exemple, lien vers le fichier) -->
</div>

<?php include 'layout/footer.php'; ?>
