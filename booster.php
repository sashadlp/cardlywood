<?php
session_start();

//indication pour le navigateur et notre fichier js
header('Content-Type: application/json');

if (!isset($_SESSION["utilisateur_id"])) {//messsage d'erreur si on essaye de recuperer un booster sans etre connecter
    echo json_encode(["error" => "  Connecte toi d'abord avant de récuperer ton booster"]);
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=login;charset=utf8", "root", "");//connexion base de donne
$utilisateurId = $_SESSION["utilisateur_id"];


$verification = $pdo->prepare("SELECT last_booster FROM utilisateurs WHERE id = ?");//variable local qui contient la date du dernier booster
$verification->execute([$utilisateurId]);
$data = $verification->fetch(PDO::FETCH_ASSOC);//recuperation du resultat de la requete

//condition de temps pour recuperer notre booster
if ($data && $data["last_booster"]) {
    if (time() - strtotime($data["last_booster"]) < 3600 ) {//si on a recuperer un booster ya moins d'une heure on nous affiche un message d'erreur nous expliquant d'attendre
        echo json_encode(["error" => "⏱️ t'inquiete contrairement au lapin blanc tu n'est pas en retard il te reste encore 1h"]);
        exit;
    }
}


$apiKey = "49c5abe2b6431ed8ce735cb4430dd600";
$page = rand(1, 10);

$url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=fr-FR&page=$page";

$reponse = file_get_contents($url);//envoie notre requete et recupere les donnée brut 

//message d'erreur en cas de non reponse de l'API
if (!$reponse) {
    echo json_encode(["error" => "API TMDB inaccessible"]);
    exit;
}

//Message d'erreur si l'api rien
$dataApi = json_decode($reponse, true);
if (!isset($dataApi["results"])) {
    echo json_encode(["error" => "Réponse API invalide"]);
    exit;
}

$films = $dataApi["results"];
//melange les film
shuffle($films);
$selected = array_slice($films, 0, 5);//selectionne 5 cartes parmi les dix pages


//ajout de notre carte dans la table cartes_utilisateurs de notre base de donnée 
$insert = $pdo->prepare("
    INSERT INTO cartes_utilisateurs 
    (utilisateur_id, film_id, nom_carte, image_url, date_obtention)
    VALUES (?, ?, ?, ?, NOW())
");
//tableau film
$resultat = [];

foreach ($selected as $film) {

    $affiche = $film["poster_path"]//construction url affiche
        ? "https://image.tmdb.org/t/p/w500" . $film["poster_path"]
        : "https://via.placeholder.com/300";
//execution de la requete faite precement
    $insert->execute([
        $utilisateurId,
        $film["id"],
        $film["title"],
        $affiche
    ]);
//ajout du titre et affiche dans le tableau
    $resultat[] = [
        "title" => $film["title"],
        "image" => $affiche
    ];
}

//met a jour dans la table utilisateurs pour donner l'heure et la date du dernier booster ouvert
$update = $pdo->prepare("UPDATE utilisateurs SET last_booster = NOW() WHERE id = ?");
$update->execute([$utilisateurId]);

//conversion en json
echo json_encode($resultat);