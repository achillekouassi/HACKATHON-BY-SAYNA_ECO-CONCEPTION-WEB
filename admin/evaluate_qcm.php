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

// Récupérer les détails de l'évaluation
$sqlEvaluation = "SELECT * FROM evaluations WHERE id = ?";
$stmtEvaluation = $pdo->prepare($sqlEvaluation);
$stmtEvaluation->execute([$evaluationId]);
$evaluation = $stmtEvaluation->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'évaluation est encore disponible
$currentTimestamp = time();
if (strtotime($evaluation['available_from']) > $currentTimestamp || strtotime($evaluation['available_until']) < $currentTimestamp) {
    // Rediriger ou afficher un message si l'évaluation n'est pas disponible
    echo "Cette évaluation n'est pas disponible actuellement.";
    exit;
}

// Vérifier si l'étudiant a déjà effectué cette évaluation
$sqlCheck = "SELECT * FROM evaluation_results WHERE evaluation_id = ? AND student_id = ?";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([$evaluationId, $userId]);
$result = $stmtCheck->fetch();

if ($result) {
    // L'utilisateur a déjà fait cette évaluation, rediriger ou afficher un message
    echo "Vous avez déjà fait cette évaluation.";
    exit;
}

// Si l'utilisateur soumet les réponses du QCM
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_answers'])) {
    // Validation et traitement des réponses du QCM
    $answers = $_POST['answers']; // Supposons que c'est un tableau d'indices ou de réponses

    // Calculer le grade (c'est un exemple simple)
    $grade = 0;
    if ($answers === $evaluation['correct_answer']) {
        $grade = 100; // Score parfait pour cet exemple
    }

    // Enregistrer le résultat de l'évaluation dans la base de données
    $sqlInsertResult = "INSERT INTO evaluation_results (evaluation_id, student_id, grade)
                        VALUES (?, ?, ?)";
    $stmtInsertResult = $pdo->prepare($sqlInsertResult);
    $stmtInsertResult->execute([$evaluationId, $userId, $grade]);

    // Redirection vers une page de confirmation ou de résultats
    header("Location: evaluation_results.php?evaluation_id=$evaluationId&course_id=$courseId");
    exit;
}
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Faire l'évaluation : <?= htmlspecialchars($evaluation['question']) ?></h1>
    <p>Type : <?= ucfirst($evaluation['type']) ?></p>
    <p>Date limite : <?= $evaluation['available_until'] ?></p>

    <form method="POST" action="">
        <!-- Afficher les questions du QCM ici -->
        <p>Question : <?= htmlspecialchars($evaluation['question']) ?></p>
        <label><input type="radio" name="answers" value="A"> <?= htmlspecialchars($evaluation['answer_a']) ?></label><br>
        <label><input type="radio" name="answers" value="B"> <?= htmlspecialchars($evaluation['answer_b']) ?></label><br>
        <label><input type="radio" name="answers" value="C"> <?= htmlspecialchars($evaluation['answer_c']) ?></label><br>

        <button type="submit" name="submit_answers" class="btn btn-primary mt-3">Soumettre</button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>
