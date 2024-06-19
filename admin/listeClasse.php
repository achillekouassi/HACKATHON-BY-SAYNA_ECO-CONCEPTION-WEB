<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Cette page n'existe pas");
}

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion d'une classe
    if (isset($_POST['action']) && $_POST['action'] === 'insert') {
        $name = $_POST['name'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("INSERT INTO classes (name, description) VALUES (:name, :description)");
            $stmt->execute([':name' => $name, ':description' => $description]);

            // Afficher le message de succès et rediriger
            echo "<script>alert('Classe insérée avec succès.'); window.location.href = 'listeClasse.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "Erreur d'insertion : " . $e->getMessage();
        }
    }

    // Modifier la classe
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("UPDATE classes SET name = :name, description = :description WHERE id = :id");
            $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);

            // Afficher le message de succès et rediriger
            echo "<script>alert('Classe mise à jour avec succès.'); window.location.href = 'listeClasse.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "Erreur de mise à jour : " . $e->getMessage();
        }
    }

    // Supprimer une classe
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM classes WHERE id = :id");
            $stmt->execute([':id' => $id]);

            // Afficher le message de succès et rediriger
            echo "<script>alert('Classe supprimée avec succès.'); window.location.href = 'listeClasse.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "Erreur de suppression : " . $e->getMessage();
        }
    }

   
}



// Sélection des classes
$stmt = $pdo->query("SELECT * FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'layout/header.php'; ?>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Classes</h3>
                <i class='bx bx-plus' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $classe): ?>
                        <tr>
                            <td><?= htmlspecialchars($classe['name']); ?></td>
                            <td><?= htmlspecialchars($classe['description']); ?></td>
                           

                            <td>
                            <a href="students_in_class.php?class_id=<?= $classe['id']; ?>"><i class='bx bxs-show icon-large'></i></a>
                              <a href="#" class="btn-view" data-classe-id="<?= $classe['id']; ?>" data-name="<?= $classe['name']; ?>" data-description="<?= $classe['description']; ?>"><i class='bx bx-edit icon-large'></i></a>
                               <a href="?action=delete&id=<?= $classe['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
                        <i class='bx bxs-trash icon-large'></i> 
                    </a>
                          </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal pour Ajouter une nouvelle classe -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nouvelle Classe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="listeClasse.php">
                    <label for="inputName" class="form-label">Nom</label>
                    <input type="text" id="inputName" name="name" class="form-control" aria-describedby="nameHelpBlock" required>
                    <label for="inputDescription" class="form-label">Description</label>
                    <input type="text" id="inputDescription" name="description" class="form-control" aria-describedby="descriptionHelpBlock" required>
        
                    <input type="hidden" name="action" value="insert">
                    <div id="error-message" style="color: red;"></div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Modifier une classe -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier Classe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="listeClasse.php">
                    <label for="editInputName" class="form-label">Nom</label>
                    <input type="text" id="editInputName" name="name" class="form-control" required>
                    <label for="editInputDescription" class="form-label">Description</label>
                    <input type="text" id="editInputDescription" name="description" class="form-control" required>
                    <input type="hidden" id="editClasseId" name="id">
                    <input type="hidden" name="action" value="update">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="saveChangesBtn" type="button" class="btn btn-primary">Modifier</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour gérer le formulaire de modification -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        const editForm = document.getElementById('editForm');
        const saveChangesBtn = document.getElementById('saveChangesBtn');

        document.querySelectorAll('.btn-view').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                const classeId = this.getAttribute('data-classe-id');

                document.getElementById('editInputName').value = name;
                document.getElementById('editInputDescription').value = description;
                document.getElementById('editClasseId').value = classeId;

                editModal.show();
            });
        });

        saveChangesBtn.addEventListener('click', function () {
            editForm.submit();
        });
    });

    function showSuccessMessageAndRedirect(message, redirectUrl, timeout) {
        alert(message);
        set
        alert(message);
        setTimeout(function() {
            window.location.href = redirectUrl;
        }, timeout);
    }
</script>



<?php
include 'layout/footer.php';
?>

