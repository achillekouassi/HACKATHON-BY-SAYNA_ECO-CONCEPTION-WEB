<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Cette page n'existe pas.");
}

if (isset($_GET['id'])) {
    $moduleId = (int) $_GET['id']; // Cast to integer for security
  
    try {
      $pdo->beginTransaction();
  
      // Prepare DELETE statement
      $stmt = $pdo->prepare("DELETE FROM modules WHERE id = :id");
      $stmt->execute([':id' => $moduleId]);
  
      $pdo->commit();
  
      header("Location: listeModule.php?message=Module+supprimé+avec+succès");
      exit;
    } catch (PDOException $e) {
      $pdo->rollBack();
      echo "Erreur lors de la suppression du module: " . $e->getMessage();
    }
  }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données du formulaire
    $name = $_POST['name'];
    $description = $_POST['description'];
    $teacherId = $_POST['teacher_id'];
    $classIds = $_POST['classIds'];

    try {
        // Commencez une transaction
        $pdo->beginTransaction();

        // Insérer le module
        $stmt = $pdo->prepare("INSERT INTO modules (name, description, teacher_id) VALUES (:name, :description, :teacher_id)");
        $stmt->execute([':name' => $name, ':description' => $description, ':teacher_id' => $teacherId]);
        
        $moduleId = $pdo->lastInsertId();

        // Lier le module aux classes en insérant dans la table module_assignments
        $stmt = $pdo->prepare("INSERT INTO module_assignments (class_id, module_id) VALUES (:class_id, :module_id)");
        foreach ($classIds as $classId) {
            $stmt->execute([':class_id' => $classId, ':module_id' => $moduleId]);
        }

        // Valider la transaction
        $pdo->commit();

        echo "<script>alert('Module ajouté avec succès.'); window.location.href = 'listeModule.php';</script>";
    } catch (PDOException $e) {
        // En cas d'erreur, annulez la transaction
        $pdo->rollBack();
        echo "Erreur lors de l'ajout du module : " . $e->getMessage();
    }
}

// Sélection des modules
$stmt = $pdo->query("SELECT m.id, m.name, m.description, u.username as professor_name, GROUP_CONCAT(c.name SEPARATOR ', ') as classes
                     FROM modules m
                     JOIN users u ON m.teacher_id = u.id
                     LEFT JOIN module_assignments ma ON m.id = ma.module_id
                     LEFT JOIN classes c ON ma.class_id = c.id
                     GROUP BY m.id, m.name, m.description, u.username");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la suppression
if (isset($_GET['id'])) {
    $moduleId = (int) $_GET['id']; // Cast to integer for security
  
    try {
      $pdo->beginTransaction();
  
      // Prepare DELETE statement
      $stmt = $pdo->prepare("DELETE FROM modules WHERE id = :id");
      $stmt->execute([':id' => $moduleId]);
  
      $pdo->commit();
  
      header("Location: listeModule.php?message=Module+supprimé+avec+succès");
      exit;
    } catch (PDOException $e) {
      $pdo->rollBack();
      echo "Erreur lors de la suppression du module: " . $e->getMessage();
    }
  }

?>

<?php include 'layout/header.php'; ?>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modules</h3>

                <i class='bx bx-plus' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Professeur</th>
                        <th>Classes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?= htmlspecialchars($module['name']); ?></td>
                            <td><?= htmlspecialchars($module['description']); ?></td>
                            <td><?= htmlspecialchars($module['professor_name']); ?></td>
                            <td><?= htmlspecialchars($module['classes']); ?></td>
                            <td>
                                <a href="editModule.php?id=<?= $module['id']; ?>"><i class='bx bx-edit icon-large'></i></a>
                                <a href="deleteModule.php?id=<?= $module['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce module ?')"><i class='bx bxs-trash icon-large '></i></a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal pour Ajouter un nouveau module -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nouveau Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="listeModule.php">
                    <div class="mb-3">
                        <label for="moduleName" class="form-label">Nom du Module</label>
                        <input type="text" id="moduleName" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="moduleDescription" class="form-label">Description du Module</label>
                        <textarea id="moduleDescription" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="teacherId" class="form-label">Professeur</label>
                        <select id="teacherId" name="teacher_id" class="form-control" required>
                            <?php
                            $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'teacher'");
                            $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($teachers as $teacher) {
                                echo "<option value='{$teacher['id']}'>{$teacher['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="classIds" class="form-label">Classes</label>
                        <select id="classIds" name="classIds[]" class="form-control" multiple required>
                            <?php
                            $stmt = $pdo->query("SELECT id, name FROM classes");
                            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($classes as $class) {
                                echo "<option value='{$class['id']}'>{$class['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->


<?php include 'layout/footer.php'; ?>
