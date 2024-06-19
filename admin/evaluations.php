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
    echo "Course ID non spécifié dans l'URL"; // Message temporaire pour déboguer
    exit;
}

$courseId = $_GET['course_id'];
$userId = $_SESSION['user_id'];

// Récupérer les évaluations pour ce cours depuis la base de données
$sql = "SELECT e.*, er.grade
        FROM evaluations e
        LEFT JOIN evaluation_results er ON e.id = er.evaluation_id AND er.student_id = ?
        WHERE e.course_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $courseId]);
$evaluations = $stmt->fetchAll();

// Récupérer les détails du cours pour l'affichage
$sqlCourse = "SELECT name, description FROM courses WHERE id = ?";
$stmtCourse = $pdo->prepare($sqlCourse);
$stmtCourse->execute([$courseId]);
$course = $stmtCourse->fetch(PDO::FETCH_ASSOC);

// Récupérer le contenu du cours pour l'affichage
$sqlCourseContent = "SELECT * FROM course_contents WHERE course_id = ?";
$stmtCourseContent = $pdo->prepare($sqlCourseContent);
$stmtCourseContent->execute([$courseId]);
$courseContents = $stmtCourseContent->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Cours : <?= htmlspecialchars($course['name'] ?? 'Cours non trouvé') ?></h1>
    <p><?= htmlspecialchars($course['description'] ?? 'Description non trouvée') ?></p>

    <br>

    <?php if (!empty($courseContents)): ?>
        <h3>Contenu du Cours</h3>
        <ul>
            <?php foreach ($courseContents as $content): ?>
                <li>
                    <h4><?= htmlspecialchars($content['title']) ?></h4>
                    <?= $content['body'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <br>

    <h3>Évaluations pour ce Cours</h3>

    <?php if (!empty($evaluations)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Question</th>
                    <th>Grade</th>
                    <th>Date Disponible</th>
                    <th>Date Limite</th>
                    <th>Action</th> <!-- Nouvelle colonne pour le bouton -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evaluations as $evaluation): ?>
                    <tr>
                        <td><?= isset($evaluation['type']) ? ucfirst($evaluation['type']) : 'Type non défini' ?></td>
                        <td><?= isset($evaluation['question']) ? htmlspecialchars($evaluation['question']) : 'Question non définie' ?></td>
                        <td><?= isset($evaluation['grade']) ? $evaluation['grade'] : 'Non évalué' ?></td>
                        <td><?= isset($evaluation['available_from']) ? $evaluation['available_from'] : 'Date non définie' ?></td>
                        <td><?= isset($evaluation['available_until']) ? $evaluation['available_until'] : 'Date non définie' ?></td>
                        <td>
                            <?php if (!isset($evaluation['grade'])): ?>
                                <?php if (strtotime($evaluation['available_from']) <= time() && strtotime($evaluation['available_until']) >= time()): ?>
                                    <a href="start_evaluation.php?evaluation_id=<?= $evaluation['id'] ?>&course_id=<?= $courseId ?>" class="btn btn-primary">Faire l'évaluation</a>
                                <?php elseif (strtotime($evaluation['available_from']) > time()): ?>
                                    <span class="text-warning">Évaluation disponible à partir de <?= $evaluation['available_from'] ?></span>
                                <?php elseif (strtotime($evaluation['available_until']) < time()): ?>
                                    <span class="text-danger">Évaluation expirée le <?= $evaluation['available_until'] ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-success">Évaluation terminée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune évaluation trouvée pour ce cours.</p>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>
