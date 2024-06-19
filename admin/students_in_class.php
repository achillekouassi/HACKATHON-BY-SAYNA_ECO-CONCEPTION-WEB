<?php
// Vérifiez si l'ID de la classe est passé en paramètre

if (!isset($_GET['class_id'])) {
    // Redirigez l'utilisateur vers une autre page ou affichez un message d'erreur
    header("Location: error.php");
    exit;
}

// Incluez le fichier de connexion à la base de données
include '../admin/db.php';

// Récupérez l'ID de la classe depuis les paramètres de la requête
$classId = $_GET['class_id'];

// Exécutez une requête SQL pour récupérer les étudiants dans cette classe avec leurs périodes
$stmt = $pdo->prepare("SELECT u.*, p.libelle AS periode FROM user_assignments ua 
                      JOIN users u ON ua.student_id = u.id 
                      JOIN periode p ON ua.periode_id = p.id 
                      WHERE ua.class_id = :class_id");
$stmt->execute([':class_id' => $classId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'layout/header.php'; ?>

<!-- MAIN -->
<main>
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Étudiants dans la classe</h3>
                <!-- Button trigger modal -->
                <i class='bx bx-plus' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Période</th>
                        <th>Nom</th>
                        <th>Prénoms</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['periode']); ?></td>
                            <td><?= htmlspecialchars($student['username']); ?></td>
                            <td><?= htmlspecialchars($student['prenom']); ?></td>
                            <td><?= htmlspecialchars($student['telephone']); ?></td>
                            <td><?= htmlspecialchars($student['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>



<?php include 'layout/footer.php'; ?>
