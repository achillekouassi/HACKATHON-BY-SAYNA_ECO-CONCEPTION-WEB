<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Cette page n'existe pas.");
}

// Supprimer une période
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM periode WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Afficher le message de succès et rediriger
        echo "<script>alert('Période supprimée avec succès.'); window.location.href = 'periode.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Erreur de suppression : " . $e->getMessage();
    }
}

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion d'une classe
    if (isset($_POST['action']) && $_POST['action'] === 'insert') {
        $libelle = $_POST['libelle'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("INSERT INTO periode (libelle, description) VALUES (:libelle, :description)");
            $stmt->execute([':libelle' => $libelle, ':description' => $description]);

            // Afficher le message de succès et rediriger
            echo "<script>alert('Classe insérée avec succès.'); window.location.href = 'periode.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "Erreur d'insertion : " . $e->getMessage();
        }
    }

  
}

// Sélection des périodes
$stmt = $pdo->query("SELECT * FROM periode");
$periode = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'layout/header.php'; ?>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Annee Academie</h3>
               
                <i class='bx bx-plus' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Annee academie</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periode as $period): ?>
                        <tr>
                            <td><?= htmlspecialchars($period['libelle']); ?></td>
                            <td><?= htmlspecialchars($period['description']); ?></td>
                           

                            <td>
                              <a href="?action=delete&id=<?= $period['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette periode ?')">
                                Supprimer
                            </a>
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
                <form method="POST" action="periode.php">
                    <label for="inputlibelle" class="form-label">Annee academy</label>
                    <input type="text" id="inputlibelle" name="libelle" class="form-control" aria-describedby="libelleHelpBlock" required>
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




<?php
include 'layout/footer.php';
?>

