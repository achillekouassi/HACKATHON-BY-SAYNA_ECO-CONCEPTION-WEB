<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Nom non défini';
$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : 'Prénom non défini';

// Function to extract initials
function getInitials($username, $prenom) {
    $initials = strtoupper(mb_substr($username, 0, 1, 'UTF-8') . mb_substr($prenom, 0, 1, 'UTF-8'));
    return $initials;
}

$initials = getInitials($username, $prenom);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
    <title>Admin</title>
  
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <span class="text"><img src="../images/logo.png" alt="logo"></span>
    </a>
    <ul class="side-menu top">
    <?php if ($role == 'admin'): ?>
        <li class="active">
            <a href="index.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
       
            <li>
                <a href="listeAdmins.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Gestion Administrateurs</span>
                </a>
            </li>
            <li>
                <a href="listeEtudiants.php">
                    <i class='bx bxs-graduation'></i>
                    <span class="text">Gestion Etudiants</span>
                </a>
            </li>
            <li>
                <a href="listeEnseignant.php">
                    <i class='bx bxs-chalkboard'></i>
                    <span class="text">Gestion Enseignants</span>
                </a>
            </li>
            <li>
                <a href="listeClasse.php">
                    <i class='bx bxs-school'></i>
                    <span class="text">Gestion Classes</span>
                </a>
            </li>
            <li>
                <a href="listeModule.php">
                    <i class='bx bxs-book'></i>
                    <span class="text">Gestion Modules</span>
                </a>
            </li>
            <li>
                <a href="gestionAffectation.php">
                    <i class='bx bxs-edit-alt'></i>
                    <span class="text">Gestion Inscription</span>
                </a>
            </li>
            <li>
                <a href="periode.php">
                    <i class='bx bxs-edit-alt'></i>
                    <span class="text">Annee Academie</span>
                </a>
            </li>

            <li>
        <a href="horaire.php">
            <i class='bx bxs-calendar'></i>
            <span class="text">Gestion des Horaires</span>
        </a>
    </li>
        <?php endif; ?>
       

        <?php if ($role == 'teacher'): ?>
        <li>
            <a href="enseignant.php">
                <i class='bx bxs-calendar'></i>
                <span class="text">Espace Professeur</span>
            </a>
        </li>

        <li>
        <a href="horaire_enseignant.php">
            <i class='bx bxs-calendar'></i>
            <span class="text">Mes Horaires</span>
        </a>
    </li>
        <?php endif; ?>
        <?php if ($role == 'student'): ?>
        <li>
            <a href="information.php">
                <i class='bx bxs-calendar'></i>
                <span class="text">Espace Etudiant</span>
            </a>
        </li>

        <li>
            <a href="horaire_etudiant.php">
                <i class='bx bxs-calendar'></i>
                <span class="text">Mes Horaires</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
 
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
<nav>
    <i class='bx bx-menu'></i>
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Rechercher...">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>
    <input type="checkbox" id="switch-mode" hidden>
  
            <a href="../login/logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
               
            </a>
       
    <label for="switch-mode" class="switch-mode"></label>
    <a href="profil.php" class="profile">
        <div class="avatar"><?php echo $initials; ?></div>
    </a>
</nav>
<main>
    <div class="head-title">
        <div class="left">
            <h1>
            <?php 
                if ($role == 'student') {
                    echo "Espace Etudiant";
                } elseif ($role == 'teacher') {
                    echo "Espace Enseignant";
                } elseif ($role == 'admin') {
                    echo "Espace Administrateur";
                }
            ?>
            </h1>
            <ul class="breadcrumb">
                <li>
                <?php echo $username . ' ' . $prenom; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- Rest of your content -->

