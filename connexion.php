<?php
session_start();

// Si la session existe déjà, on redirige vers l'espace interne sans passer par le formulaire
if (isset($_SESSION["utilisateur_id"])) {
    header("Location: espaceinterne.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
        <link rel="icon" href="Logo-a4.ico" type="image/x-icon">

    <title>Connexion - Cardllywood</title>
</head>
<body id="body">
    
    <div class="section section1"> 
        <img src="Logo-a4.png" height="150" width="150">
        <h1><a href="index.php">Cardllywood</a></h1>
        <button id="boutonmenu">☰</button>
        <button id="BoutonTheme">Passer en mode clair</button>
    </div>

    <div id="Menu">
        <a href="index.html">Accueil</a>
        <a href="Boutique.html">Boutique</a>
        <a href="place du marché.html">Place du marché</a>
        <a href="Ma collection.html">Ma collection</a>
        <a href="A propos de nous.html">À propos de nous</a>
    </div>

    <h1>Connexion</h1>
    <form method="POST" action="login.php">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="motdepasse" required>
        <button type="submit">Se connecter</button>
    </form>
    
    <h6>si vous n'avez pas de compte cliquer sur le lien ci dessous</h6>
    <a href="inscription.php">inscription</a>
<img src="singing.jpg" class="background-img">

   <script>
        // Sauvegarde l'email si connexion réussie
        const emailInput = document.getElementById('email');
        const form = document.querySelector('form');

        // Pré-remplir au chargement
        const emailSauvegarde = localStorage.getItem('email_utilisateur');
        if (emailSauvegarde) {
            emailInput.value = emailSauvegarde;
        }

        //save
        form.addEventListener('submit', () => {
            localStorage.setItem('email_utilisateur', emailInput.value);
        });
    </script>
   
</body>

</html>
