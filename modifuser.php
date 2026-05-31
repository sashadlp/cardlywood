<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location: connexion.html");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');

$id = $_SESSION["utilisateur_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $motdepasse = trim($_POST["motdepasse"]);

    if (!empty($nom) && !empty($email) && !empty($motdepasse)) {

        $motdepasseHash = password_hash($motdepasse, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE utilisateurs 
                               SET nom = :nom, email = :email, motdepasse = :motdepasse
                               WHERE id = :id");

        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'motdepasse' => $motdepasseHash,
            'id' => $id
        ]);

        $_SESSION["nom"] = $nom;
        $_SESSION["email"] = $email;

        $message = "Informations mises à jour avec succès !";
    }
}

$stmt = $pdo->prepare("SELECT nom, email FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mes informations</title>
	    <link rel="stylesheet" href="main.css">
        <link rel="icon" href="Logo a4.ico" type="image/x-icon">

</head>
<body>
<body>
    <div id="Menu">
    <a href="index.php">Accueil</a>
    <a href="connexion.html">Espace interne</a>
            <a href="logout.php">Se déconnecter</a><br><br>

</div>



<body id="body">
    <div class="section section1"> 
        <img src="Logo a4.png" alt="" height="150" width="150">
        <h1><a href="index.php">Cardllywood</a></h1>
        
        <button id="boutonmenu">☰</button><!--bouton pour ouvrir notre menu latteral-->
    </div>
<h1>Modifier mes informations</h1>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="POST">
    
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
    <br><br>

    <label>Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <br><br>

    <label>Nouveau mot de passe :</label>
    <input type="password" name="motdepasse" required>
    <br><br>

    <button type="submit">Mettre à jour</button>
</form>
<img src="singing.jpg" class="background-img">

<br>
<a href="espaceinterne.php">Retour</a>
<script src="espaceintern.js"></script>
<script src="main2.js"></script>
<script src="filtres.js"></script>
</body>
</html>