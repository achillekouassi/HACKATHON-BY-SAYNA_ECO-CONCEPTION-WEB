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
$moduleId = isset($_GET['module_id']) ? $_GET['module_id'] : null;

if (!$moduleId) {
    // Rediriger si aucun module_id n'est fourni
    header("Location: information.php");
    exit;
}

// Vérifier si l'utilisateur est enseignant et s'il est responsable de ce module
$sql = "SELECT * FROM modules WHERE id = ? AND teacher_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId, $userId]);
$module = $stmt->fetch();

if (!$module) {
    // Rediriger si l'utilisateur n'est pas responsable de ce module
    header("Location: information.php");
    exit;
}

// Récupérer les classes liées à ce module
$sql = "SELECT c.id as class_id, c.name as class_name 
        FROM module_assignments ma 
        JOIN classes c ON ma.class_id = c.id 
        WHERE ma.module_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId]);
$classes = $stmt->fetchAll();

// Récupérer les cours liés à ce module
$sql = "SELECT * FROM courses WHERE module_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId]);
$courses = $stmt->fetchAll();


?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <h2><?= htmlspecialchars($module['name']) ?></h2>
    <hr>
    <h2>Classes Associées</h2>
    <ul>
        <?php foreach ($classes as $class): ?>
            <li><a href="class_info.php?class_id=<?= htmlspecialchars($class['class_id']) ?>"><?= htmlspecialchars($class['class_name']) ?></a></li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <h2>Gestion des cours</h2>
    <form action="add_course.php" method="post">
    <input type="hidden" name="module_id" value="<?= htmlspecialchars($moduleId) ?>">
    
    <div class="form-group">
        <label for="course_name">Nom du cours</label>
        <input type="text" name="course_name" id="course_name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="course_description">Description</label>
        <textarea name="course_description" id="course_description" class="form-control" rows="5" required></textarea>
    </div>
    <br>
    <button type="submit" class="btn btn-info">Ajouter le cours</button>
</form>
<br><br>
<hr>

    <h2>Cours Existants</h2>
    <div class="row">
        <?php foreach ($courses as $course): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($course['description']) ?></p>
                        <a href="course_info.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-info">Voir cours</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
