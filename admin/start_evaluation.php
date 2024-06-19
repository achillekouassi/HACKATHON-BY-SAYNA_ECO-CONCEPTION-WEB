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

// Vérifier si l'utilisateur a déjà effectué cette évaluation
$sqlCheck = "SELECT * FROM evaluation_results WHERE evaluation_id = ? AND student_id = ?";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([$evaluationId, $userId]);
$result = $stmtCheck->fetch();

if ($result) {
    // L'utilisateur a déjà fait cette évaluation, rediriger ou afficher un message
    echo "Vous avez déjà fait cette évaluation.";
    exit;
}

// Rediriger l'utilisateur vers la page de l'évaluation (QCM ou autre)
// Vous pouvez définir ici la logique pour rediriger en fonction du type d'évaluation

// Exemple de redirection vers une page QCM spécifique
header("Location: evaluate_qcm.php?evaluation_id=$evaluationId&course_id=$courseId");
exit;
