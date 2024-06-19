<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: ../login/login");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../admin/db.php';

// Récupérer les données du formulaire
$moduleId = isset($_POST['module_id']) ? $_POST['module_id'] : null;
$courseName = isset($_POST['course_name']) ? $_POST['course_name'] : null;
$courseDescription = isset($_POST['course_description']) ? $_POST['course_description'] : null;

if ($moduleId && $courseName && $courseDescription) {
    // Ajouter le cours dans la base de données
    $sql = "INSERT INTO courses (module_id, name, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$moduleId, $courseName, $courseDescription]);

    // Rediriger vers la page du module après l'ajout
    header("Location: module_info.php?module_id=" . $moduleId);
    exit;
} else {
    // Gérer le cas où les données ne sont pas correctement fournies
    header("Location: module_info.php?module_id=" . $moduleId);
    exit;
}
?>
