<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authorized as admin
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

if ($role !== 'admin') {
    echo "Accès interdit!";
    exit;
}

// Include database connection
include 'db.php';

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize variables to store form data
    $user_id = $_POST['user_id'] ?? '';
    $module_id = $_POST['module_id'] ?? '';
    $classe_id = $_POST['classe_id'] ?? '';
    $jour = $_POST['jour'] ?? '';
    $heure_debut = $_POST['heure_debut'] ?? '';
    $heure_fin = $_POST['heure_fin'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $google_meet = $_POST['google_meet'] ?? '';

    // Prepare SQL statement to insert new schedule
    $sql_insert = "INSERT INTO emploi_du_temps 
                   (user_id, module_id, classe_id, jour, heure_debut, heure_fin, date_debut, date_fin, google_meet) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);

    // Execute SQL statement with parameters
    $success = $stmt_insert->execute([$user_id, $module_id, $classe_id, $jour, $heure_debut, $heure_fin, $date_debut, $date_fin, $google_meet]);

    // Check if insertion was successful
    if ($success) {
        $response = ['status' => 'success', 'message' => 'Horaire attribué avec succès!'];
    } else {
        $response = ['status' => 'error', 'message' => 'Erreur lors de l\'attribution de l\'horaire : ' . $stmt_insert->errorInfo()[2]];
    }

    // Output response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Pagination settings
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 7;
$offset = ($page - 1) * $records_per_page;

// Retrieve schedules with pagination
$sql = "SELECT emploi_du_temps.jour, emploi_du_temps.heure_debut, emploi_du_temps.heure_fin, modules.name AS nom_module, 
                CONCAT(users.username, ' ', users.prenom) AS nom_professeur, classes.name AS classe, 
                emploi_du_temps.google_meet, emploi_du_temps.date_debut, emploi_du_temps.date_fin 
        FROM emploi_du_temps
        JOIN modules ON emploi_du_temps.module_id = modules.id
        JOIN users ON emploi_du_temps.user_id = users.id
        JOIN classes ON emploi_du_temps.classe_id = classes.id
        ORDER BY emploi_du_temps.date_debut ASC
        LIMIT :offset, :records_per_page";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total rows for pagination
$total_stmt = $pdo->query("SELECT COUNT(*) AS total FROM emploi_du_temps");
$total_rows = $total_stmt->fetch()['total'];
$total_pages = ceil($total_rows / $records_per_page);

// Retrieve teachers
$sql_professeurs = "SELECT id, username FROM users WHERE role = 'teacher'";
$stmt_professeurs = $pdo->query($sql_professeurs);
$professeurs = $stmt_professeurs->fetchAll(PDO::FETCH_ASSOC);

// Retrieve modules
$sql_modules = "SELECT id, name FROM modules";
$stmt_modules = $pdo->query($sql_modules);
$modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);

// Retrieve classes
$sql_classes = "SELECT id, name FROM classes";
$stmt_classes = $pdo->query($sql_classes);
$classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Include header -->
<?php include 'layout/header.php'; ?>
        <div class="table-data">
            <div class="order">
            <div class="head">
                <h3>Administrateurs</h3>
                <!-- Button trigger modal -->
                <i class='bx bx-plus' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Module</th>
                            <th>Classe</th>
                            <th>Professeur</th>
                            <th>Heure</th>
                            <th>Date de Début</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horaires as $horaire): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($horaire['jour']); ?></td>
                            <td><?php echo htmlspecialchars($horaire['nom_module']); ?></td>
                            <td><?php echo htmlspecialchars($horaire['classe']); ?></td>
                            <td><?php echo htmlspecialchars($horaire['nom_professeur']); ?></td>
                            <td><?php echo htmlspecialchars($horaire['heure_debut']); ?> à <?php echo htmlspecialchars($horaire['heure_fin']); ?></td>
                            <td><?php echo htmlspecialchars($horaire['date_debut']); ?> à <?php echo htmlspecialchars($horaire['date_fin']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Précédent</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i === $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Suivant</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

</main>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nouvel Horaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAjoutHoraire">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Professeur</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Sélectionner Professeur</option>
                            <?php foreach ($professeurs as $professeur): ?>
                            <option value="<?php echo htmlspecialchars($professeur['id']); ?>"><?php echo htmlspecialchars($professeur['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="module_id" class="form-label">Module</label>
                        <select class="form-control" id="module_id" name="module_id" required>
                            <option value="">Sélectionner Module</option>
                            <?php foreach ($modules as $module): ?>
                            <option value="<?php echo htmlspecialchars($module['id']); ?>"><?php echo htmlspecialchars($module['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="classe_id" class="form-label">Classe</label>
                        <select class="form-control" id="classe_id" name="classe_id" required>
                            <option value="">Sélectionner Classe</option>
                            <?php foreach ($classes as $classe): ?>
                            <option value="<?php echo htmlspecialchars($classe['id']); ?>"><?php echo htmlspecialchars($classe['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jour" class="form-label">Jour</label>
                        <select class="form-control" id="jour" name="jour" required>
                            <option value="Lundi">Lundi</option>
                            <option value="Mardi">Mardi</option>
                            <option value="Mercredi">Mercredi</option>
                            <option value="Jeudi">Jeudi</option>
                            <option value="Vendredi">Vendredi</option>
                            <option value="Samedi">Samedi</option>
                            <option value="Dimanche">Dimanche</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="heure_debut" class="form-label">Heure de Début</label>
                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
                    </div>
                    <div class="mb-3">
                        <label for="heure_fin" class="form-label">Heure de Fin</label>
                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_debut" class="form-label">Date de Début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Date de Fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                    </div>
                    <div class="mb-3">
                        <label for="google_meet" class="form-label">Lien Google Meet</label>
                        <input type="text" class="form-control" id="google_meet" name="google_meet">
                    </div>
                    <div id="error-message" style="color: red;"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<!-- Inclure jQuery depuis un CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Votre script JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formAjoutHoraire = document.getElementById('formAjoutHoraire');

    formAjoutHoraire.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(formAjoutHoraire);

        fetch('horaire.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Réponse du serveur:', data);
            if (data.status === 'success') {
                alert('Horaire attribué avec succès!');
                $('#exampleModal').modal('hide');
                location.reload(); // Recharger la page pour mettre à jour la liste des horaires
            } else {
                document.getElementById('error-message').textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout :', error);
            alert('Erreur lors de l\'ajout : ' + error.message);
        });
    });
});
</script>



<!-- Include footer -->
<?php include 'layout/footer.php'; ?>
