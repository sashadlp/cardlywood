//bouton menu burger
document.addEventListener("DOMContentLoaded", () => {
    const btnMenu = document.getElementById("boutonmenu");
    const menuDiv = document.getElementById("Menu");

    if (btnMenu && menuDiv) {
        btnMenu.addEventListener("click", () => {
            menuDiv.classList.toggle("show");
        });
    }
});
//code js du modal
document.addEventListener("DOMContentLoaded", () => {
    const updateButton = document.getElementById("tradeCardsBtn"); 
    const cancelButton = document.getElementById("cancel");      
    const dialog = document.getElementById("favDialog");          
    const tradeForm = dialog ? dialog.querySelector("form") : null;
    const messageDiv = document.getElementById("trade-message"); 

    if (updateButton && dialog) {//verifie et ouvre le menu modal 
        updateButton.addEventListener("click", () => {
            dialog.showModal();
        });
    }

    if (cancelButton && dialog) {//ferme le bouton modal
        cancelButton.addEventListener("click", () => {
            dialog.close();
            if (messageDiv) messageDiv.textContent = ""; 
        });
    }

    if (tradeForm) {//code du formulaire
        tradeForm.addEventListener("submit", async (e) => {
            e.preventDefault(); 

            //recuperation de l'email et de la carte choisie
            const emailInput = document.getElementById("email");
            const cardSelect = document.getElementById("UserCard"); 

            
            if (!emailInput || !cardSelect) return;

            const formData = new FormData(tradeForm);

            if (messageDiv) {
                messageDiv.style.color = "#ffffff";
                messageDiv.textContent = "Traitement du transfert en cours...";
            }

            try {
                const response = await fetch("echange.php", {//envoie des donnée saisie dans le formulaire vers echange.php
                    method: "POST",
                    body: formData
                });

                const data = await response.json();
                
                //dans le cas ou echange.php confirme l'echange 
                if (data.status === "success") {
                    if (messageDiv) {
                        messageDiv.style.color = "#37d50b"; 
                        messageDiv.textContent = data.message;
                    }
                    
                    setTimeout(() => {//delais avant reinitialisation de la page apres envoie 
                        dialog.close();
                        tradeForm.reset();
                        if (messageDiv) messageDiv.textContent = "";
                        window.location.reload(); 
                    }, 2000);

                } else {//message d'erreur en cas de soucis lors de l'echange cote echnge.php
                    
                    if (messageDiv) {
                        messageDiv.style.color = "#ff3333"; 
                        messageDiv.textContent = data.message;
                    }
                }

            } catch (error) {//message d'erreur probleme de serveur
                console.error("Erreur lors de l'échange :", error);
                if (messageDiv) {
                    messageDiv.style.color = "#ff3333";
                    messageDiv.textContent = "Impossible de contacter le serveur d'échange.";
                }
            }
        });
    }
});


document.addEventListener("DOMContentLoaded", () => {
    const grid = document.getElementById("movie-grid");

    if (grid) {
       //ecoute de click sur l'ensemble de la grille
        grid.addEventListener("click", (e) => {
            //lors du clique on cherche l'element le plus proche ayant la class movie-card
            const card = e.target.closest(".movie-card"); 
            
            if (card) {
                // On récupère l'ID du film stocké dans la carte (ex: data-id="550")
                const filmId = card.getAttribute("data-id");
                
                if (filmId) {
                    //modifie l'url pour nous mettre sur la page detailler de la carte selectionner
                window.location.href = `cartes-detailler.html?id=${filmId}`;                }
            }
        });
    }
});