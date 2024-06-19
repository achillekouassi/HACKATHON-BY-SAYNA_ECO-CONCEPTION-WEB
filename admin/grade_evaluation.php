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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evaluationId = $_POST['evaluation_id'];
    $responseIds = $_POST['response_ids'];
    $grades = $_POST['grades'];

    for ($i = 0; $i < count($responseIds); $i++) {
        $responseId = $responseIds[$i];
        $grade = $grades[$i];

        // Mettre à jour la note dans la base de données seulement si le champ n'est pas désactivé
        if ($responseId && $grade) {
            $sql = "UPDATE evaluation_results SET grade = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$grade, $responseId]);
        }
    }

    // Rediriger vers la page des détails de l'évaluation avec le paramètre success
    header("Location: view_evaluation_details.php?evaluation_id=" . $evaluationId . "&success=1");
    exit;
}
?>
