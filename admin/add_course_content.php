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

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentTitle = $_POST['content_title'];
    $contentBody = $_POST['content_body'];

    // Insérer le contenu du cours dans la base de données
    $sql = "INSERT INTO course_contents (course_id, title, body) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$courseId, $contentTitle, $contentBody]);

    // Rediriger vers la page des détails du cours
    header("Location: course_info.php?course_id=$courseId");
    exit;
}
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <h1>Ajouter le contenu du cours</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="content_title">Titre</label>
            <input type="text" name="content_title" id="content_title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content_body">Contenu</label>
            <textarea name="content_body" id="content_body" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter le contenu</button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>

<!-- Inclure CKEditor -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content_body');
</script>
