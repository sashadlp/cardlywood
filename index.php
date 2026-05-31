<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Green Dragon</title>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/Flip.min.js"></script>
    <link rel="icon" href="Logo-a4.ico" type="image/x-icon">

    <link rel="stylesheet" href="main.css">

</head>

<body id="body">

    <div class="section section1">

        <a href="index.php" class="logo-link">    
        <div class="box2">
                <img src="Logo-a4.png" alt="Logo" height="150" width="150">
            </div>
        </a>

        <h1>Cardllywood</h1>

        <button id="boutonmenu">☰</button>

       
        </div>

    </div>
<img src="singing.jpg" class="background-img">
<div class="projecteur proj-gauche"></div>
<div class="projecteur proj-droite"></div>
    <div id="Menu">
        <a href="index.php">Accueil</a>
        <a href="iot.html">Projet iot</a>
        <a href="connexion.php">Espace interne</a>
    </div>
    
    <section class="booster-section">
        <h2> Ton booster quotidien</h2>
        <p>Ouvre ton pack et débloque 5 cartes toutes les heures !</p>
        <button id="BoutonBooster" class="badge-nouveau">
             Ouvrir mon booster
        </button>
        <div id="booster-resultat"></div>
    </section>

    <section class="api-gallery">

        <h2>Les cartes a la une </h2>
        <br>

        <div class="filter-container" style="text-align: center; margin-bottom: 25px;">
            <button class="bouton-filtre active" data-genre="all">Tous</button>
            <button class="bouton-filtre" data-genre="28">Action</button>
            <button class="bouton-filtre" data-genre="16">Animation</button>
            <button class="bouton-filtre" data-genre="18">Drame</button>
            <button class="bouton-filtre" data-genre="14">Fantasy</button>
            <button class="bouton-filtre" data-genre="27">Horreur</button>
            <button class="bouton-filtre" data-genre="878">Science fiction</button>
            <button class="bouton-filtre" data-genre="53">Trhiller</button>
            

        </div>

        <div id="movie-grid" class="movie-grid">
        </div>

    </section>

    <button class="btn-flotant" id="tradeCardsBtn">
        <img src="reverse.jpg" alt="Carte Reverse">
    </button>

    <dialog id="favDialog">
        <form method="dialog">
            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <p>
                    <div>
                        <label for="email">Email Destinataire</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    
                    <br>
                    
                    <label for="UserCard">Carte que vous souhaitez echanger:</label>
                    <select id="UserCard" name="userCard" required>
                        <option value="">-- Choisi la carte que tu veux ceder --</option>
                        <?php 
                            // Connexion sécurisée à la base de données
                            $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');

                            $utilisateur = $_SESSION['utilisateur_id'];
                            //instanciation des cartes que l'utilisateur possede 
                            $usercard = $pdo->prepare('SELECT film_id, nom_carte FROM cartes_utilisateurs WHERE utilisateur_id = ?');
                            
                            $usercard->execute([$utilisateur]);
                            
                            while ($cartes = $usercard->fetch()) {
                                echo '<option value="' . $cartes['film_id'] . '">' . htmlspecialchars($cartes['nom_carte']) . '</option>';
                            }
                            $usercard->closeCursor();
                        ?>
                    </select>
                </p>
                <div id="trade-message"></div>
                <div>
                    <button id="cancel" type="reset">Annuler</button>
                    <button type="submit">Valider</button>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 10px;">
                    Connecte toi avant de donner tes cartes
                </p>
                <div style="text-align: center;">
                    <a href="connexion.php" style="color: #37d50b;">Connecte toi</a>
                </div>
                <br>
                <div>
                    <button type="button" onclick="document.getElementById('favDialog').close()">Fermer</button>
                </div>
            <?php endif; ?>
        </form>
    </dialog>
    
    <script src="main2.js"></script> 
    <script src="booster.js"></script>
    <script src="filtres.js"></script>

</body>
 <footer>
    <h2 class="footer-text">credit sasha_dlp©️ </h2>
    <a href="https://www.instagram.com/sasha_.dlp/">
        <img src="instagram.png" alt="" class="footer-img">
    </a>
    <a href="https://www.youtube.com/@sashadlp"><img src="youtube.png" alt="" class="footer-img"></a>
    <a href="https://open.spotify.com/show/58ydyQR4PYFGku6OdjhlM7?si=PBsm5s_3RYyGaf9__wOkMg"><img src="spotify.png" class="footer-img"></a>
    <p>
    <a href="https://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="https://jigsaw.w3.org/css-validator/images/vcss"
            alt="CSS Valide !" />
    </a>
</p>
 </footer>
</html>