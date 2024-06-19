<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../login/login");
    exit;
}



include '../admin/db.php';

// Compter les utilisateurs actifs ayant le rôle admin
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS student_count FROM users WHERE status = 'active' AND role = 'student'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $studentCount = $result['student_count'];
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS teacher_count FROM users WHERE status = 'active' AND role = 'teacher'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $teacherCount = $result['teacher_count'];
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS module_count FROM modules ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $moduleCount = $result['module_count'];
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Récupérer la liste des utilisateurs depuis la base de données
try {
    $stmt = $pdo->query("SELECT * FROM users WHERE status = 'active'");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}


?>


<?php
include 'layout/header.php';
?>
<?php if ($role == 'admin'): ?>
<!-- CONTENT -->

<!-- NAVBAR -->

<!-- MAIN -->


    <ul class="box-info">
        <li>
           <i class='bx bxs-graduation'></i>
            <span class="text">
                <h3><?= $studentCount; ?></h3>
                <p>Etudiants</p>
            </span>
        </li>
        <li>
        <i class='bx bxs-chalkboard'></i>
            <span class="text">
            <h3><?= $teacherCount; ?></h3>
                <p>Enseignants</p>
            </span>
        </li>
        <li>
        <i class='bx bxs-book'></i>
            <span class="text">
            <h3><?= $moduleCount; ?></h3>
                <p>Modules</p>
            </span>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Utilisateurs</h3>
               
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prenoms</th>
                        <th>Telephone</th>
						<th>Email</th>
                        <th>Statut</th>
                      
                    </tr>
                </thead>
				<tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['username']; ?></td>
                            <td><?= $user['prenom']; ?></td>
                            <td><?= $user['telephone']; ?></td>
							<td><?= $user['email']; ?></td>
                            <td><?= $user['role']; ?></td>
            

</tr>
<?php endforeach; ?>
</tbody>
</table>
        </div>
        <?php endif; ?>

<?php if ($role == 'teacher' || $role == 'student'): ?>
<h1>Cette page n'est pas disponible ...</h1>
    <?php endif; ?>
</main>
<!-- MAIN -->

<?php
include 'layout/footer.php';
?>



