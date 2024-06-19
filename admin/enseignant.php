<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: ../login/login.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../admin/db.php';

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$userData = $stmt->fetch();

// Vérifier le rôle de l'utilisateur
$role = $userData['role'];

if ($role === 'student') {
    // Récupérer les informations spécifiques aux étudiants
    $sql = "SELECT u.*, ua.class_id, c.name as class_name 
            FROM users u 
            JOIN user_assignments ua ON u.id = ua.student_id 
            JOIN classes c ON ua.class_id = c.id 
            WHERE u.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $studentData = $stmt->fetch();

    if ($studentData) {
        $classId = $studentData['class_id'];

        // Récupérer les modules de la classe de l'étudiant
        $sql = "SELECT m.id as module_id, m.name as module_name, m.description, m.teacher_id 
                FROM modules m 
                JOIN module_assignments ma ON m.id = ma.module_id 
                WHERE ma.class_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$classId]);
        $modules = $stmt->fetchAll();

        // Récupérer les enseignants des modules
        $teacherIds = array_column($modules, 'teacher_id');
        if (!empty($teacherIds)) {
            $teacherIds = implode(',', $teacherIds);
            $sql = "SELECT id, username as teacher_name 
                    FROM users 
                    WHERE id IN ($teacherIds)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $teachers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Keyed by id
        }

        // Récupérer les notes de l'étudiant
        $sql = "SELECT g.*, m.name as module_name 
                FROM grades g 
                JOIN modules m ON g.module_id = m.id 
                WHERE g.student_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $grades = $stmt->fetchAll();
    }
} elseif ($role === 'teacher') {
    // Récupérer les modules enseignés par l'enseignant
    $sql = "SELECT m.id as module_id, m.name as module_name, m.description 
            FROM modules m 
            WHERE m.teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $modules = $stmt->fetchAll();

    if ($modules) {
        // Récupérer les classes associées à chaque module
        $moduleIds = array_column($modules, 'module_id');
        if (!empty($moduleIds)) {
            $moduleIds = implode(',', $moduleIds);
            $sql = "SELECT ma.module_id, c.name as class_name 
                    FROM module_assignments ma 
                    JOIN classes c ON ma.class_id = c.id 
                    WHERE ma.module_id IN ($moduleIds)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $classes = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
        }
    }
}

// Afficher les informations spécifiques à l'utilisateur
?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <hr>
    <h1>Modules</h1>
    <div class="row">
        <?php if ($role === 'student' && isset($studentData)): ?>
            <div class="col-lg-3">
                <h2>Modules</h2>
                <?php if (isset($modules)): ?>
                    <?php foreach ($modules as $module): ?>
                        <div class="card mb-4">
                            <a href="module_info_p.php?module_id=<?= htmlspecialchars($module['module_id']) ?>">
                                <div class="card-body text-center">
                                    <h4><?= htmlspecialchars($module['module_name']) ?></h4>
                                </div>  
                            </a> 
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun module trouvé.</p>
                <?php endif; ?>
            </div>
        
            <?php elseif ($role === 'teacher'): ?>
    <div class="col-lg-3">
        
        <?php if (isset($modules)): ?>
            <?php foreach ($modules as $module): ?>
                <div class="card mb-3">
                    <a href="module_info_p.php?module_id=<?= htmlspecialchars($module['module_id']) ?>">
                        <div class="card-body text-center">
                            <h4><?= htmlspecialchars($module['module_name']) ?></h4>
                            <h5>Classes</h5>
                            <?php if (isset($classes[$module['module_id']])): ?>
                                <ul class="list-unstyled">
                                    <?php foreach ($classes[$module['module_id']] as $class): ?>
                                        <li><?= htmlspecialchars($class['class_name']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>Aucune classe trouvée.</p>
                            <?php endif; ?>
                        </div>  
                    </a> 
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun module trouvé.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
