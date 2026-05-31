<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=login','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERREUR LORS DE LA CONNEXION A LA  BDD'. $e->getMessage();
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email= trim($_POST['email']);
    $password= trim($_POST['motdepasse']);
    $name= trim($_POST['nom']);

    if (!empty($email)&& !empty($password)&& !empty($name)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $requete= $pdo->prepare('INSERT INTO utilisateurs (email, motdepasse,nom) 
        VALUES (:email, :motdepasse, :nom)');

        $requete->execute([
            'email' => $email,
            'motdepasse' => $hashedPassword,
            'nom'=> $name
        ]);
        echo'Inscription reussi';
       
    }else{ 
        echo 'Veuillez renseigner tous les champs';
    }
}
?>