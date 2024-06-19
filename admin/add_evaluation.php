<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../admin/db.php';

$courseId = isset($_GET['course_id']) ? $_GET['course_id'] : null;

if (!$courseId) {
    header("Location: information.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evaluationType = $_POST['evaluation_type'];
    $availableFrom = $_POST['available_from'];
    $availableUntil = $_POST['available_until'];

    if ($evaluationType === 'qcm') {
        $questions = $_POST['questions'];

        foreach ($questions as $question) {
            $questionText = $question['question'];
            $answerA = $question['answer_a'];
            $answerB = $question['answer_b'];
            $answerC = $question['answer_c'];
            $correctAnswer = $question['correct_answer'];

            $sql = "INSERT INTO evaluations (course_id, type, question, answer_a, answer_b, answer_c, correct_answer, available_from, available_until) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$courseId, 'qcm', $questionText, $answerA, $answerB, $answerC, $correctAnswer, $availableFrom, $availableUntil]);
        }

    } elseif ($evaluationType === 'file') {
        if ($_FILES['evaluation_file']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['evaluation_file']['name'];
            $filePath = '../uploads/' . $fileName;

            move_uploaded_file($_FILES['evaluation_file']['tmp_name'], $filePath);

            $sql = "INSERT INTO evaluations (course_id, type, file_name, file_path, available_from, available_until) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$courseId, 'file', $fileName, $filePath, $availableFrom, $availableUntil]);
        }
    }

    header("Location: course_info.php?course_id=$courseId");
    exit;
}
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1 class="mt-4">Ajouter une évaluation</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="evaluation_type" id="evaluation_type">
        
        <div class="form-group">
            <label>Type d'évaluation</label>
           <br>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="evaluation_type" id="qcm" value="qcm">
                    <label class="form-check-label" for="qcm">QCM</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="evaluation_type" id="file" value="file">
                    <label class="form-check-label" for="file">Fichier à télécharger</label>
                </div>
            </div>
        </div>
<br>
        <div id="qcm-form" style="display: none;">
            <div id="questions-container">
                <div class="question-group">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" name="questions[0][question]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="answer_a">Réponse A</label>
                        <input type="text" name="questions[0][answer_a]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="answer_b">Réponse B</label>
                        <input type="text" name="questions[0][answer_b]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="answer_c">Réponse C</label>
                        <input type="text" name="questions[0][answer_c]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="correct_answer">Réponse correcte</label>
                        <select name="questions[0][correct_answer]" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <button type="button" id="add-question" class="btn btn-secondary">Ajouter une autre question</button>
           
        </div>
        <br>

        <div id="file-form" style="display: none;">
            <div class="form-group">
                <label for="evaluation_file">Sélectionnez le fichier</label>
                <input type="file" name="evaluation_file" id="evaluation_file" class="form-control-file">
            </div>
        </div>

        <div class="form-group">
            <label for="available_from">Date et heure de début de disponibilité</label>
            <input type="datetime-local" name="available_from" id="available_from" class="form-control" required>
        </div>
        <br>
        <div class="form-group">
            <label for="available_until">Date et heure de fin de disponibilité</label>
            <input type="datetime-local" name="available_until" id="available_until" class="form-control">
        </div>
<br>
        <button type="submit" class="btn btn-primary">Ajouter l'évaluation</button>
    </form>
</div>


<?php include 'layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const qcmForm = document.getElementById('qcm-form');
    const fileForm = document.getElementById('file-form');
    const qcmRadio = document.getElementById('qcm');
    const fileRadio = document.getElementById('file');
    const addQuestionButton = document.getElementById('add-question');
    const questionsContainer = document.getElementById('questions-container');
    let questionIndex = 1;

    qcmRadio.addEventListener('change', function () {
        if (qcmRadio.checked) {
            qcmForm.style.display = 'block';
            fileForm.style.display = 'none';
        }
    });

    fileRadio.addEventListener('change', function () {
        if (fileRadio.checked) {
            fileForm.style.display = 'block';
            qcmForm.style.display = 'none';
        }
    });

    addQuestionButton.addEventListener('click', function () {
        const questionGroup = document.createElement('div');
        questionGroup.classList.add('question-group');

        questionGroup.innerHTML = `
            <div class="form-group">
                <label for="question">Question</label>
                <input type="text" name="questions[${questionIndex}][question]" class="form-control">
            </div>
            <div class="form-group">
                <label for="answer_a">Réponse A</label>
                <input type="text" name="questions[${questionIndex}][answer_a]" class="form-control">
            </div>
            <div class="form-group">
                <label for="answer_b">Réponse B</label>
                <input type="text" name="questions[${questionIndex}][answer_b]" class="form-control">
            </div>
            <div class="form-group">
                <label for="answer_c">Réponse C</label>
                <input type="text" name="questions[${questionIndex}][answer_c]" class="form-control">
            </div>
            <div class="form-group">
                <label for="correct_answer">Réponse correcte</label>
                <select name="questions[${questionIndex}][correct_answer]" class="form-control">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
        `;

        questionsContainer.appendChild(questionGroup);
        questionIndex++;
    });
});
</script>
