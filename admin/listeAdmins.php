<?php
session_start();
include '../admin/db.php';

// Vérifiez que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Cette page n'existe pas");
}
// Traitement du formulaire d'insertion et 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'insert') {
        $username = $_POST['username'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $password = '1234'; // Mot de passe par défaut
        $role = 'admin'; // Rôle par défaut

        // Vérifier si le numéro de téléphone est valide
        if (!preg_match("/^[0-9]{10}$/", $telephone)) {
            echo "<script>document.getElementById('error-message').innerText = 'Le numéro de téléphone doit être composé de 10 chiffres.';</script>";
            exit; // Arrêter l'exécution du script
        }

        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                echo "<script>document.getElementById('error-message').innerText = 'Cet email est attribué à un utilisateur dans le système.';</script>";
            } else {
                // Insérer un nouvel utilisateur
                $hashedPassword = md5($password); // Vous devriez utiliser une méthode plus sécurisée pour hacher le mot de passe
                $stmt = $pdo->prepare("INSERT INTO users (username, prenom, telephone, email, password, role) VALUES (:username, :prenom, :telephone, :email, :password, :role)");
                $stmt->execute([':username' => $username, ':prenom' => $prenom, ':telephone' => $telephone, ':email' => $email, ':password' => $hashedPassword, ':role' => $role]);

                // Afficher le message de succès pendant 3 secondes avant de rediriger
                echo "<script>showSuccessMessageAndRedirect('Compte étudiant créé avec succès.', 'listeAdmins.php', 3000);</script>";
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }
}



// Traitement de la suppression
if(isset($_GET['action']) && $_GET['action'] === 'delete') {
    if(isset($_GET['userId'])) {
        $userId = $_GET['userId'];
        try {
            // Modifier l'utilisateur pour le rendre inactif au lieu de le supprimer
            $stmt = $pdo->prepare("UPDATE users SET status = 'inactive' WHERE id = :userId");
            $stmt->execute([':userId' => $userId]);

            // Rediriger vers la page de liste des administrateurs
            header('Location: listeAdmins.php');
            exit;
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }
}

// Récupérer la liste des utilisateurs depuis la base de données
try {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'admin' AND status = 'active'");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action']) && $_POST['action'] === 'update') {
        $userId = $_POST['userId'];
        $username = $_POST['username'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];

        // Vérifier si le numéro de téléphone est valide
        if (!preg_match("/^[0-9]{10}$/", $telephone)) {
            echo "Le numéro de téléphone doit être composé de 10 chiffres.";
            exit; // Arrêter l'exécution du script
        }

        try {
            // Mettre à jour l'utilisateur
            $stmt = $pdo->prepare("UPDATE users SET username = :username, prenom = :prenom, telephone = :telephone, email = :email WHERE id = :userId");
            $stmt->execute([':username' => $username, ':prenom' => $prenom, ':telephone' => $telephone, ':email' => $email, ':userId' => $userId]);

            // Afficher le message de succès et rediriger
            echo "<script>alert('Utilisateur modifié avec succès.'); window.location.href = 'listeAdmins.php';</script>";
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }
}
?>

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
                        <th>Nom</th>
                        <th>Prenoms</th>
                        <th>Telephone</th>
						<th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
				<tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['username']; ?></td>
                            <td><?= $user['prenom']; ?></td>
                            <td><?= $user['telephone']; ?></td>
							<td><?= $user['email']; ?></td>
                            <td>
                    <a href="#" class="btn-view" data-user-id="<?= $user['id']; ?>" data-username="<?= $user['username']; ?>" data-prenom="<?= $user['prenom']; ?>" data-telephone="<?= $user['telephone']; ?>" data-email="<?= $user['email']; ?>">
                        <i class='bx bxs-show icon-large'></i> 
                    </a>
                    <a href="?action=delete&userId=<?= $user['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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

<!-- Popup Form -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nouvel Administrateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <label for="inputUsername" class="form-label">Nom</label>
                        <input type="text" id="inputUsername" name="username" class="form-control" aria-describedby="usernameHelpBlock">
                        <label for="inputPrenom" class="form-label">Prénom</label>
                        <input type="text" id="inputPrenom" name="prenom" class="form-control" aria-describedby="prenomHelpBlock">
                        <label for="inputTelephone" class="form-label">Téléphone</label>
                        <input type="text" id="inputTelephone" name="telephone" class="form-control" aria-describedby="telephoneHelpBlock">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" id="inputEmail" name="email" class="form-control" aria-describedby="emailHelpBlock">
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

<!-- Nouvelle Modal pour la Modification -->

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier Administrateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <label for="editInputUsername" class="form-label">Nom</label>
                    <input type="text" id="editInputUsername" name="username" class="form-control">
                    <label for="editInputPrenom" class="form-label">Prénom</label>
                    <input type="text" id="editInputPrenom" name="prenom" class="form-control">
                    <label for="editInputTelephone" class="form-label">Téléphone</label>
                    <input type="text" id="editInputTelephone" name="telephone" class="form-control">
                    <label for="editInputEmail" class="form-label">Email</label>
                    <input type="email" id="editInputEmail" name="email" class="form-control">
                    <input type="hidden" id="editUserId" name="userId">
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
                const username = this.getAttribute('data-username');
                const prenom = this.getAttribute('data-prenom');
                const telephone = this.getAttribute('data-telephone');
                const email = this.getAttribute('data-email');
                const userId = this.getAttribute('data-user-id');

                document.getElementById('editInputUsername').value = username;
                document.getElementById('editInputPrenom').value = prenom;
                document.getElementById('editInputTelephone').value = telephone;
                document.getElementById('editInputEmail').value = email;
                document.getElementById('editUserId').value = userId;

                editModal.show();
            });
        });

        saveChangesBtn.addEventListener('click', function () {
            editForm.submit();
        });
    });

    function showSuccessMessageAndRedirect(message, redirectUrl, timeout) {
            alert(message);
            setTimeout(function() {
                window.location.href = redirectUrl;
            }, timeout);
        }

</script>



<?php
include 'layout/footer.php';
?>


