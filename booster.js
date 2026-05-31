document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("BoutonBooster");//recuperation de l'id boosterbtn
    const result = document.getElementById("booster-resultat");

    btn.addEventListener("click", async () => {

        const res = await fetch("booster.php");//envoie de requete au fichier booster
        const data = await res.json();

        if (data.error) {
            result.innerHTML =  data.error;//affichage des message d'erreur 
            return;
        }

        result.innerHTML = "";

//recuperation et affichage des cartes recuperer 
        data.forEach(card => {
            const div = document.createElement("div");//creation d'une div qui servira de conteneur
           //injection du html
            div.innerHTML = `
                <img src="${card.image}" width="120">
                <p>${card.title}</p>
            `;
            result.appendChild(div);
        });

    });
});