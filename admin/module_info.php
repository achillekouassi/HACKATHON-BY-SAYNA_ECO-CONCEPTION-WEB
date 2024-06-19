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

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Vérifier que l'ID du module est fourni
if (!isset($_GET['module_id'])) {
    // Rediriger vers la page d'information principale s'il n'y a pas d'ID de module
    header("Location: information.php");
    exit;
}

$moduleId = $_GET['module_id'];

// Récupérer les informations du module depuis la base de données, y compris le nom d'utilisateur et le prénom de l'enseignant
$sql = "SELECT m.*, u.username as teacher_name, u.prenom as teacher_prenom
        FROM modules m 
        LEFT JOIN users u ON m.teacher_id = u.id 
        WHERE m.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId]);
$moduleData = $stmt->fetch();

if (!$moduleData) {
    // Rediriger vers la page d'information principale si le module n'est pas trouvé
    header("Location: information.php");
    exit;
}

// Récupérer les notes de l'étudiant pour ce module
$sql = "SELECT g.* 
        FROM grades g 
        WHERE g.student_id = ? AND g.module_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $moduleId]);
$grades = $stmt->fetchAll();

// Récupérer les cours associés à ce module depuis la table course_contents
$sql = "SELECT cc.* 
        FROM course_contents cc
        JOIN courses c ON cc.course_id = c.id
        WHERE c.module_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId]);
$courses = $stmt->fetchAll();

?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Informations du Module</h1>
    <div class="row">
        <div class="col-lg-6">
            <ul>
                <li>Nom du module: <?= htmlspecialchars($moduleData['name']) ?></li>
                <li>Enseignant: <?= htmlspecialchars($moduleData['teacher_name']) ?> <?= htmlspecialchars($moduleData['teacher_prenom']) ?></li>
            </ul>
        </div>
    </div>

    <h2>Cours associés :</h2>
    <ul>
        <div class="row">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-3">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">
                                    <a href="course_detail.php?course_id=<?= $course['course_id'] ?>" class="course-title"><?= htmlspecialchars($course['title']) ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun cours trouvé pour ce module.</li>
            <?php endif; ?>
        </div>
    </ul>
</div>

<?php include 'layout/footer.php'; ?>
