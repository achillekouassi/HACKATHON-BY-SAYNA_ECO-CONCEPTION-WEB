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

// Récupérer l'ID de l'évaluation depuis l'URL
$evaluationId = isset($_GET['evaluation_id']) ? $_GET['evaluation_id'] : null;

if (!$evaluationId) {
    // Rediriger si aucun evaluation_id n'est fourni
    header("Location: view_evaluations.php?course_id=" . $_GET['course_id']);
    exit;
}

// Récupérer les détails de l'évaluation
$sql = "SELECT * FROM evaluations WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$evaluationId]);
$evaluation = $stmt->fetch();

if (!$evaluation) {
    // Rediriger si l'évaluation n'existe pas
    header("Location: view_evaluations.php?course_id=" . $_GET['course_id']);
    exit;
}

// Récupérer les réponses des étudiants
$sql = "SELECT er.*, u.username, u.prenom
        FROM evaluation_results er
        JOIN users u ON er.student_id = u.id
        WHERE er.evaluation_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$evaluationId]);
$responses = $stmt->fetchAll();
?>

<?php include 'layout/header.php'; ?>

<div class="container">
 
    <hr>
    <h3>Notes des étudiants</h3>
    <form id="gradeForm" action="grade_evaluation.php" method="post">
        <input type="hidden" name="evaluation_id" value="<?= $evaluationId ?>">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Prénom</th>
                    <th>Note attribuée</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($responses as $response): ?>
                    <tr>
                        <td><?= htmlspecialchars($response['username']) ?></td>
                        <td><?= htmlspecialchars($response['prenom']) ?></td>
                    
                        <td>
                            <input type="hidden" name="response_ids[]" value="<?= $response['id'] ?>">
                            <input type="number" name="grades[]" min="0" max="20" required data-initial-value="<?= isset($response['grade']) ? htmlspecialchars($response['grade']) : '' ?>" value="<?= isset($response['grade']) ? htmlspecialchars($response['grade']) : '' ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Attribuer Note</button>
    </form>
</div>

<script>
document.getElementById('gradeForm').addEventListener('submit', function(event) {
    const responseIds = document.querySelectorAll('input[name="response_ids[]"]');
    const grades = document.querySelectorAll('input[name="grades[]"]');
    
    responseIds.forEach((responseId, index) => {
        const grade = grades[index];
        if (grade.value === grade.dataset.initialValue) {
            grade.disabled = true;
            responseId.disabled = true;
        }
    });
});

window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        alert('Enregistrement effectué');
    }
});
</script>

<?php include 'layout/footer.php'; ?>
