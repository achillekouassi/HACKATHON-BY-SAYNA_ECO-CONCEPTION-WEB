<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est autorisé à gérer les affectations
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin')) {
    die("Cette pas n'existe pas");
}

// Récupérer la liste des étudiants
$stmt_students = $pdo->query("SELECT id, username FROM users WHERE role = 'student' AND status='active'");
$students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des classes
$stmt_classes = $pdo->query("SELECT id, name FROM classes");
$classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des periode
$stmt_periods = $pdo->query("SELECT id, libelle FROM periode");
$periods = $stmt_periods->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les affectations existantes
$stmt_assignments = $pdo->query("SELECT ua.id, u.username AS student_name, c.name AS class_name
                                 FROM user_assignments ua
                                 JOIN users u ON ua.student_id = u.id
                                 JOIN classes c ON ua.class_id = c.id");
$assignments = $stmt_assignments->fetchAll(PDO::FETCH_ASSOC);

// Traiter les soumissions de formulaire pour ajouter ou modifier les affectations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_id'], $_POST['class_id'], $_POST['periode_id'])) {
        $studentId = $_POST['student_id'];
        $classId = $_POST['class_id'];
        $periodeId = $_POST['periode_id'];

        // Vérifier si l'affectation existe déjà pour cet étudiant
        $stmt_existing_assignment = $pdo->prepare("SELECT COUNT(*) AS count FROM user_assignments WHERE student_id = :student_id AND periode_id = :periode_id");
        $stmt_existing_assignment->execute([':student_id' => $studentId, ':periode_id' => $periodeId]);
        $existing_assignment = $stmt_existing_assignment->fetch(PDO::FETCH_ASSOC);

        if ($existing_assignment['count'] > 0) {
            // Mettre à jour l'affectation existante
            $stmt_update_assignment = $pdo->prepare("UPDATE user_assignments SET class_id = :class_id WHERE student_id = :student_id AND periode_id = :periode_id");
            $stmt_update_assignment->execute([':class_id' => $classId, ':student_id' => $studentId, ':periode_id' => $periodeId]);
            echo "<script>alert('Affectation mise à jour avec succès.');</script>";
        } else {
            // Ajouter une nouvelle affectation
            $stmt_add_assignment = $pdo->prepare("INSERT INTO user_assignments (student_id, class_id, periode_id) VALUES (:student_id, :class_id, :periode_id)");
            $stmt_add_assignment->execute([':student_id' => $studentId, ':class_id' => $classId, ':periode_id' => $periodeId]);
            echo "<script>alert('Affectation ajoutée avec succès.');</script>";
        }
    }
}
?>

<?php include 'layout/header.php'; ?>


      <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Gestion des inscriptions</h3>
                <!-- Button trigger modal -->
            </div>
            <form method="POST" action="">
        <label for="student">Étudiant :</label>
        <select id="student" name="student_id" class="form-select form-select-lg mb-3">
        <option >Choisir un étudiant</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= $student['id']; ?>"><?= $student['username']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="class">Classe :</label>
        <select id="class" name="class_id" class="form-select form-select-lg mb-3">
        <option >Choisir une classe</option>
            <?php foreach ($classes as $class): ?> 
                <option value="<?= $class['id']; ?>"><?= $class['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="periode">Année Académique :</label>
        <select id="periode" name="periode_id" class="form-select form-select-lg mb-3">
        <option >Choisir une période</option>
            <?php foreach ($periods as $period): ?>
                <option value="<?= $period['id']; ?>"><?= $period['libelle']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit" class="btn btn-success md-2">Enregistrer</button>
    </form>
</div>
</div>
</main>

<?php include 'layout/footer.php'; ?>
