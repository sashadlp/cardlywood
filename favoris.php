<?php
session_start();

// verification de la connexion
if (!isset($_SESSION["utilisateur_id"]) ) {
    echo "Erreur";
    exit;
}

$id_user = $_SESSION["utilisateur_id"];
$id_film = $_POST['film_id'];

$host = 'localhost';
$dbname = 'login';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);//connexion base de donnée
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "Erreur";
    exit;
}

//verification de l'etat de la carte voir si elle est like ou pas
$checkQuery = $pdo->prepare("
    SELECT is_favorite 
    FROM cartes_utilisateurs 
    WHERE utilisateur_id = ? AND film_id = ?
");
$checkQuery->execute([$id_user, $id_film]);//envoie de notre requete
$carte = $checkQuery->fetch(PDO::FETCH_ASSOC);//requete pour recuperer la ligne demander pour ensuite verifier si ma carte existe ou pas

if (!$carte) {//verification si la carte existe 
    echo "Erreur : Carte introuvable";
    exit;
}


if ($carte['is_favorite'] == 0) {//cas ou ma carte n'est pas encore favorite
   

    $update = $pdo->prepare("UPDATE cartes_utilisateurs SET is_favorite = 1 WHERE utilisateur_id = ? AND film_id = ?");
    $update->execute([$id_user, $id_film]);
    
   
    
    echo "La carte selectioner a bien ete ajouter  a tes favorite";

} else {//cas si ma carte est deja favorite 
    $update = $pdo->prepare("UPDATE cartes_utilisateurs SET is_favorite = 0 WHERE utilisateur_id = ? AND film_id = ?");
    $update->execute([$id_user, $id_film]);  
    echo "La carte selectionner ne fait plus partie de tes favorite";
}