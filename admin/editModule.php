<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Cette pae n'existe pas");
}

$moduleId = $_GET['id'];

// Récupérer les informations du module
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = :id");
$stmt->execute([':id' => $moduleId]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    die("Module non trouvé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $teacherId = $_POST['teacher_id'];
    $classIds = $_POST['classIds'];

    try {
        // Commencez une transaction
        $pdo->beginTransaction();

        // Mettre à jour le module
        $stmt = $pdo->prepare("UPDATE modules SET name = :name, description = :description, teacher_id = :teacher_id WHERE id = :id");
        $stmt->execute([':name' => $name, ':description' => $description, ':teacher_id' => $teacherId, ':id' => $moduleId]);

        // Supprimer les anciennes affectations de classe
        $stmt = $pdo->prepare("DELETE FROM module_assignments WHERE module_id = :module_id");
        $stmt->execute([':module_id' => $moduleId]);

        // Ajouter les nouvelles affectations de classe
        $stmt = $pdo->prepare("INSERT INTO module_assignments (class_id, module_id) VALUES (:class_id, :module_id)");
        foreach ($classIds as $classId) {
            $stmt->execute([':class_id' => $classId, ':module_id' => $moduleId]);
        }

        // Valider la transaction
        $pdo->commit();

        echo "<script>alert('Module mis à jour avec succès.'); window.location.href = 'listeModule.php';</script>";
    } catch (PDOException $e) {
        // En cas d'erreur, annulez la transaction
        $pdo->rollBack();
        echo "Erreur lors de la mise à jour du module : " . $e->getMessage();
    }
}

// Sélectionnez les professeurs disponibles
$stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'teacher'");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sélectionnez toutes les classes disponibles
$stmt = $pdo->query("SELECT id, name FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sélectionnez les classes associées au module
$stmt = $pdo->prepare("SELECT class_id FROM module_assignments WHERE module_id = :module_id");
$stmt->execute([':module_id' => $moduleId]);
$assignedClasses = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include 'layout/header.php'; ?>



    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier le Module</h3>
            </div>
            <form method="POST" action="editModule.php?id=<?= $moduleId; ?>">
                <div class="mb-3">
                    <label for="moduleName" class="form-label">Nom du Module</label>
                    <input type="text" id="moduleName" name="name" class="form-control" value="<?= htmlspecialchars($module['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="moduleDescription" class="form-label">Description du Module</label>
                    <textarea id="moduleDescription" name="description" class="form-control" required><?= htmlspecialchars($module['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="teacherId" class="form-label">Professeur</label>
                    <select id="teacherId" name="teacher_id" class="form-control" required>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id']; ?>" <?= $teacher['id'] == $module['teacher_id'] ? 'selected' : ''; ?>><?= $teacher['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="classIds" class="form-label">Classes</label>
                    <select id="classIds" name="classIds[]" class="form-control" multiple required>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id']; ?>" <?= in_array($class['id'], $assignedClasses) ? 'selected' : ''; ?>><?= $class['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" onclick="window.location.href='listeModule.php'">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Bootstrap JS -->


<?php include 'layout/footer.php'; ?>
