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

// Récupérer l'ID du cours
$courseId = isset($_GET['course_id']) ? $_GET['course_id'] : null;

if (!$courseId) {
    // Rediriger si aucun course_id n'est fourni
    header("Location: information.php");
    exit;
}

// Récupérer les informations du cours
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    // Rediriger si le cours n'existe pas
    header("Location: information.php");
    exit;
}

// Récupérer le contenu du cours
$sql = "SELECT * FROM course_contents WHERE course_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$contents = $stmt->fetchAll();

// Récupérer les informations du module associé
$sql = "SELECT * FROM modules WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$course['module_id']]);
$module = $stmt->fetch();
?>
<?php include 'layout/header.php'; ?>

<div class="container">
    <h2> <?= htmlspecialchars($course['name']) ?></h2>
    <hr>
  
    <div class="d-flex justify-content-between">
        <a href="add_course_content.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-primary">Ajouter le contenu du cours</a>
        <a href="add_evaluation.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-secondary">Ajouter une évaluation</a>
        <a href="view_evaluations.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-info">Voir les évaluations</a>
    </div>
    <hr>
    <h2>Contenu du cours</h2>
    <?php foreach ($contents as $content): ?>
        <div>
            <h3><?= htmlspecialchars($content['title']) ?></h3>
            <div><?= htmlspecialchars_decode($content['body']) ?></div>
        </div>
        <hr>
    <?php endforeach; ?>
</div>

<?php include 'layout/footer.php'; ?>
