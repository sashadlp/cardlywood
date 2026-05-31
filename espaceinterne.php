<?php
session_start();

$host = 'localhost';
$dbname = 'login';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);//connexion a la base donnée
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {//message erreur
    die('Erreur : ' . $e->getMessage());
}

// Vérification qu'on est bien connecté
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION["utilisateur_id"];

//requete recuperation depuis la base de donne nos carte ainsi que trie par ordre
$query = $pdo->prepare("
    SELECT * FROM cartes_utilisateurs 
    WHERE utilisateur_id = ?
    ORDER BY is_favorite DESC, nom_carte ASC
");

$query->execute([$id_user]); //envoie de notre requete
$mes_cartes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cardllywood - Espace Interne</title>
<link rel="icon" href="Logo-a4.ico" type="image/x-icon">
<link rel="stylesheet" href="main.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

<style>

</style>
</head>

<body id="body">

<div id="Menu">
    <div class="user-controls">
        <a href="modifuser.php">Modifier ses informations</a><br><br>
        <a href="logout.php">Se déconnecter</a><br><br>

        <form action="deleteuser.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
            <button type="submit">Supprimer mon compte</button>
        </form>
    </div>
</div>
<img src="singing.jpg" class="background-img">

<div class="section section1"> 
    <div class="logo-debut"> <img src="Logo-a4.png" alt="Logo" height="150" width="150"></div>
   
    <h1><a href="index.php">Cardllywood</a></h1>
    <button id="boutonmenu">☰</button>
</div>

<h1>Bienvenue !</h1>

<p>
Connecté en tant que : 
<strong><?= htmlspecialchars($_SESSION["nom"] ?? 'Utilisateur') ?></strong>
</p>

<br>

<h2>Ma Collection de Cartes</h2>

<div class="collection-container">

<?php foreach ($mes_cartes as $carte): ?>
    <div class="card-box <?= $carte['is_favorite'] == 1 ? 'is-liked' : '' ?>" 
         data-id="<?= $carte['film_id'] ?>" 
         data-liked="<?= $carte['is_favorite'] == 1 ? 'true' : 'false' ?>">
        
        <div class="card-image-wrapper">
            <img src="<?= htmlspecialchars($carte['image_url']) ?>" alt="Carte">
            
            <i class="like-btn fa fa-2x <?= $carte['is_favorite'] == 1 ? 'fa-heart liked' : 'fa-heart-o' ?>" aria-hidden="true"></i>
        </div>

        <p><?= htmlspecialchars($carte['nom_carte']) ?></p>
    </div>
<?php endforeach; ?>

</div>

<div id="background"></div>

<script src="espaceintern.js"></script>
<script src="main2.js"></script>


</body>
</html>