const cleapi = "49c5abe2b6431ed8ce735cb4430dd600";
const urlapi = "https://image.tmdb.org/t/p/w500";


const urlParams = new URLSearchParams(window.location.search);//analyse de l'url
const Id_Film = urlParams.get('id');//recuperation de l'id depuis l'url

async function detailFilm() {
    try {
        
        const reponse = await fetch(`https://api.themoviedb.org/3/movie/${Id_Film}?api_key=${cleapi}&language=fr-FR`);//connexion a l'api pour recuperer les donnée du film selectionner
        const Donnee_Film = await reponse.json();//traduction de la reponse en json

        const real = await fetch(`https://api.themoviedb.org/3/movie/${Id_Film}/credits?api_key=${cleapi}`);//recuperation du cast complet du film
        const donne_real = await real.json();


        //recherche dans le casting qui a le role de director donc real
        let nom_real = "Inconnu";//valeur par defaut au cas ou si lors de la recherche on trouve pas le real
        if (donne_real.crew) {
            for (let membre of donne_real.crew) {
                if (membre.job === 'Director') {
                    nom_real = membre.name;
                    break;
                }
            }
        }

        //recuperation de l'annee de sortie, la durée et l'affiche
        const annee_sortie = Donnee_Film.release_date ? Donnee_Film.release_date.substring(0, 4) : "Inconnue";
        const Duree = Donnee_Film.runtime ? Donnee_Film.runtime + " minutes" : "Inconnue";//inconnue au cas ou on a pas les info
        const affichee = Donnee_Film.poster_path ? urlapi + Donnee_Film.poster_path : "https://via.placeholder.com/500x750?text=Pas+d+image";

       
        const conteneur = document.getElementById('details-film');

      //verification et injection des donnée recuperer precedement
        if (conteneur) {//verifie que le conteneur existe
            conteneur.innerHTML = `
                <div class="bloc-image">
                    <img src="${affichee}" alt="Affiche de ${Donnee_Film.title}" class="affiche-detail">
                </div>
                
                <div class="bloc-infos">
                    <h2>${Donnee_Film.title || 'Titre inconnu'}</h2>
                    <p><strong>Année de sortie :</strong> ${annee_sortie}</p>
                    <p><strong>Durée :</strong> ${Duree}</p>
                    <p><strong>Réalisateur :</strong> ${nom_real}</p>
                </div>
            `;
        }
        
        console.log("Données du film chargées :", Donnee_Film);
//message d'erreur 
    } catch (erreur) {
        console.error("Nous n'avons pas réussi à récupérer le film :", erreur);
        document.getElementById('details-film').innerHTML = "<p style='color: red;'>Erreur recuperation des information</p>";
    }
}


detailFilm();