// Gestion du message de bienvenue en il s'adapte en fonction du nom de la personne connecter
let prenomSauvegarde = localStorage.getItem("prenom");
if (prenomSauvegarde !== null && document.getElementById("titre")) {
    document.getElementById("titre").textContent = "Bienvenue, " + prenomSauvegarde + " !";
}

// Gestion du bouton like
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); 
        
        // recuperation des  carte parente
        const card = this.closest('.card-box');
        const filmId = card.getAttribute('data-id')
        
        //verifie si ma carte est liker ou pas et modifie sa classe en fonction
        card.classList.toggle('is-liked');
        const isLiked = card.classList.contains('is-liked');
        
        
        //  Changement visuel du cœur 
        if (isLiked) {//si ma carte est pas deja liker
            this.classList.remove('fa-heart-o');
            this.classList.add('fa-heart', 'liked');
        } else {//si ma carte est deja liker
            this.classList.remove('fa-heart', 'liked');
            this.classList.add('fa-heart-o');
        }
        fetch('favoris.php', {
            method: 'POST',//methode d'envoie
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',//indication serveur sur la formee des donnée envoyer
            },
            body: 'film_id=' + filmId//contenue requete
        })
        .then(response => response.text())//conversion de la reponse en text
        .then(data => {
            // Si le serveur renvoie une erreur, on remet le cœur a l'etat dans lequel il etait avant qu'on click dessus
            if (data.trim() === "Erreur") {
                alert("Une erreur est survenue, impossible d'enregistrer le favori.");
                this.classList.toggle('fa-heart-o');
                this.classList.toggle('fa-heart');
                this.classList.toggle('liked');
            }
        });
        
    });
});