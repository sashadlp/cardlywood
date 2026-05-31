<?php
session_start();

//verification que l'utilisateur est bien connecter avant de pouvoir echanger
if (!isset($_SESSION['utilisateur_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Veuillez vous connecter."
    ]);
    exit;
}

//message d'erreur si l'un des champs requis n'est pas remplie
if(!isset($_POST['email']) || !isset($_POST['userCard'])){
    echo json_encode([
        "status"=>"error",
        "message"=>"Tu n'as pas répondu à tout le formulaire, réponds-y avant de valider."
    ]);
    exit;
}

$id_donnateur = $_SESSION['utilisateur_id'];
$mail_receveur = $_POST['email'];
$Film_id = $_POST['userCard']; 

try {
    $pdo = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $demandeUtilisateur = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");//recuperation dans la base de donnée du mail renseigné par le donnateur
    $demandeUtilisateur->execute([$mail_receveur]);//envoie requete injection mail 
    $destinataire = $demandeUtilisateur->fetch(PDO::FETCH_ASSOC);//recuperation resultat de la requete

    if(!$destinataire){//message d'erreur si l'utilisateur n'existe pas 
       echo json_encode([
            "status" => "error",
            "message" => "Ce compte n'existe pas, vérifie l'orthographe."
        ]);
        exit; 
    }
    $id_destinataire = $destinataire['id'];//recup id utilisateur

    $demandeCarte = $pdo->prepare('SELECT film_id, nom_carte, image_url FROM cartes_utilisateurs WHERE utilisateur_id = ? AND film_id = ?');//requete sql verif si utilisateur 
    $demandeCarte->execute([$id_donnateur, $Film_id]);//envoie de la requete
    $cartePossedee = $demandeCarte->fetch(PDO::FETCH_ASSOC);//recuperation resultat requete

    if (!$cartePossedee) {//message erreur si pas proprietaire de la carte
        echo json_encode([
            "status" => "error",
            "message" => "Vous ne possédez pas cette carte dans votre collection."
        ]);
        exit;
    }

    $nom_carte = $cartePossedee['nom_carte'];//recuperation et stockage du nom de la carte selectionner
    $image_url = $cartePossedee['image_url'];//recuperation et stockage du lien de l'image associer au film selection
    $filmId = $cartePossedee['film_id'];//recuperation et stockage de l'id du film

    $pdo->beginTransaction();
//suppression de la carte donnée coter donnateur
    $suppresionCarte = $pdo->prepare('DELETE FROM cartes_utilisateurs WHERE utilisateur_id = ? AND film_id = ? LIMIT 1');//suppresion de la carte donnée dans la base de donnée du donnateur
    $suppresionCarte->execute([$id_donnateur, $Film_id]);
//ajout de la carte cote receveur
    $ajoutercarte = $pdo->prepare("INSERT INTO cartes_utilisateurs (utilisateur_id, film_id, nom_carte, image_url) VALUES (?, ?, ?, ?)");//requete sql pour que le receveur recuper la carte donnée
    $ajoutercarte->execute([$id_destinataire, $filmId, $nom_carte, $image_url]);

    $pdo->commit();

    echo json_encode([//message de confirmation
        "status" => "success",
        "message" => "Félicitations, le cadeau a été envoyé !"
    ]);

} catch (Exception $e) {//permet d'annuler l'echange si y a un soucis
    if (isset($pdo) && $pdo->inTransaction()) {//verifie qu'on etait bien connecter la base de donnée 
        $pdo->rollBack();
    }
    echo json_encode([///message d'erreur en cas de bug lors de l'echange
        "status" => "error",
        "message" => "Erreur système : " . $e->getMessage()
    ]);
}