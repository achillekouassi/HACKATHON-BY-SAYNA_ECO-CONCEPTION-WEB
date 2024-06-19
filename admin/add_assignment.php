<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../admin/db.php';

// Récupérer les données du formulaire
$moduleId = $_POST['module_id'];
$assignmentName = $_POST['assignment_name'];
$assignmentDescription = $_POST['assignment_description'];

// Ajouter le devoir dans la base de données
$sql = "INSERT INTO assignments (module_id, name, description) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moduleId, $assignmentName, $assignmentDescription]);

// Rediriger vers la page du module
header("Location: module_info.php_p?module_id=$moduleId");
exit;
?>
