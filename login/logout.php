<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Si la session est active, la détruire
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header("Location: ../index.php");
exit;
?>
