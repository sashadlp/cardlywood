<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location: connexion.html");
    exit;
}

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');


$id = $_SESSION["utilisateur_id"];

// Supprimer l'utilisateur
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $id]);


session_unset();
session_destroy();


header("Location: connexion.html");
exit;
?>