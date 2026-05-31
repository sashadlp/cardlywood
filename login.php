<?php
session_start(); // Démarrer la session

// Connexion à la base de données avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=login','root','');



// Récupérer et nettoyer les données du formulaire

// Récupérer les données du formulaire
if (!empty($_POST['email']) && !empty($_POST['motdepasse'])){
$email = trim($_POST['email' ] );

$password = trim($_POST['motdepasse' ]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {
echo "Email invalide.";
exit;

}
}

// Valider l'email

if (!empty($email) && !empty($password) ) {
// Préparer la requête

$stmt = $pdo->prepare("SELECT id, nom, email, motdepasse FROM utilisateurs WHERE email = :email");
$stmt->execute( ['email' => $email]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilisateur && password_verify($password, $utilisateur['motdepasse'] ) ) {
$_SESSION['utilisateur_id' ] = $utilisateur['id' ];
$_SESSION['nom' ] = $utilisateur['nom' ] ;
$_SESSION['email' ] = $utilisateur['email'];

// Redirection vers la page d'accueil
header('Location: espaceinterne.php');
exit;
} else {
$erreur = "Email ou mot de passe incorrect.";
}
}

