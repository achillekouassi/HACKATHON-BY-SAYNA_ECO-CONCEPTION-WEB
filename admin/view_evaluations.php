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

// Récupérer l'ID du cours depuis l'URL
$courseId = isset($_GET['course_id']) ? $_GET['course_id'] : null;

if (!$courseId) {
    // Rediriger si aucun course_id n'est fourni
    header("Location: information.php");
    exit;
}

// Récupérer les informations sur le cours
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    // Rediriger si le cours n'existe pas
    header("Location: information.php");
    exit;
}

// Récupérer les évaluations associées à ce cours
$sql = "SELECT e.*, COUNT(er.student_id) as student_count
        FROM evaluations e
        LEFT JOIN evaluation_results er ON e.id = er.evaluation_id
        WHERE e.course_id = ?
        GROUP BY e.id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$evaluations = $stmt->fetchAll();
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <h1>Évaluations pour le cours: <?= htmlspecialchars($course['name']) ?></h1>
    <hr>
    <div class="list-group">
        <?php foreach ($evaluations as $evaluation): ?>
            <div class="list-group-item">
                <?php if (isset($evaluation['title'])): ?>
                    <h2><?= htmlspecialchars($evaluation['title']) ?></h2>
                <?php endif; ?>
                <?php if (isset($evaluation['description'])): ?>
                    <p>Description: <?= htmlspecialchars($evaluation['description']) ?></p>
                <?php endif; ?>
                <?php if (isset($evaluation['type'])): ?>
                    <p>Type d'évaluation: <?= htmlspecialchars($evaluation['type']) ?></p>
                    <?php if ($evaluation['type'] === 'qcm'): ?>
                        <p>Question: <?= htmlspecialchars($evaluation['question']) ?></p>
                        <p>Réponse correcte: <?= htmlspecialchars($evaluation['correct_answer']) ?></p>
                    <?php elseif ($evaluation['type'] === 'file'): ?>
                        <p>Nom du fichier: <?= htmlspecialchars($evaluation['file_name']) ?></p>
                        <p>Chemin du fichier: <?= htmlspecialchars($evaluation['file_path']) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <p>Nombre d'étudiants ayant effectué l'évaluation: <?= $evaluation['student_count'] ?></p>
                <a href="view_evaluation_details.php?evaluation_id=<?= $evaluation['id'] ?>" class="btn btn-primary">Voir les détails de l'évaluation</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
