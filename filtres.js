const API_KEY = '49c5abe2b6431ed8ce735cb4430dd600';
const GRID_CONTAINER = document.getElementById('movie-grid');

// Liste des ID de films à récupérer 
const idFilm = [
    829, 4011, 201085, 268, 3933, 75, 608, 354912,
    11, 1891, 1892, 599, 1933, 603, 807, 756,
    694, 215, 299536, 299534, 389, 680, 13, 550,
    278, 238, 424, 497, 64682, 504608, 82695, 14836,1273221,274,914,630,872,15340,945729,289,539,426063,1078605,1430077,1062722,333339,1061474,9078,2062,38757,11621,11886,123
];
 //reglage affichage
document.addEventListener('DOMContentLoaded', () => {
    if (GRID_CONTAINER) {
        GRID_CONTAINER.style.display = 'flex';
        GRID_CONTAINER.style.flexWrap = 'wrap';
        GRID_CONTAINER.style.flexDirection = 'row';
    }
    fetchMovies();
});


//recuperation et affichage d'une liste de film
async function fetchMovies() {
    if (!GRID_CONTAINER) return;
    GRID_CONTAINER.innerHTML = "";//nettoyage de notre grille 

  for (const id of idFilm) {
        try {
            const response = await fetch(//requete a l'api
                `https://api.themoviedb.org/3/movie/${id}?api_key=${API_KEY}&language=fr-FR`
            );
            const movie = await response.json();
            
            displayMovie(movie);
        } catch (error) {//message d'erreur
            console.error("Erreur chargement film ID:", id, error);
        }
    }

    initFiltersLogic();
}

//recuperation et affichage des affiches du film
function displayMovie(movie) {
    if (!movie || !movie.id) return;

    const posterUrl = movie.poster_path//constante url affiche
        ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
        : 'https://via.placeholder.com/500x750?text=Pas+d+image';

    const cartesfilm = document.createElement('div');//creation d'une nouvelle div ou s'afficherons nos cartes
    cartesfilm.classList.add('cartesfilm');//ajout de notre class css
    
    const genreId = movie.genres ? movie.genres.map(g => g.id).join(',') : '';//recuperation id de genre
    cartesfilm.setAttribute('data-genre', genreId);//stockage id de genre

    //injection de la structure html 
    cartesfilm.innerHTML = `
        <img src="${posterUrl}" alt="${movie.title || 'Affiche du film'}" class="movie-poster" style="width: 300px; height: 400px; border-radius: 8px;">
        <h3 class="movie-title" style="margin-top: 10px; font-size: 1.1em;">${movie.title || 'Titre inconnu'}</h3>
    `;
    cartesfilm.addEventListener('click', () => {//permet de changer de page quand on clique sur notre carte pour en avoir le detail
    window.location.href = `cartes-detailler.html?id=${movie.id}`;
});

    GRID_CONTAINER.appendChild(cartesfilm);
}
//fonction de filtrage
function initFiltersLogic() {
    const boutonfiltre = document.querySelectorAll('.bouton-filtre');
    const carteFilm = document.querySelectorAll('.cartesfilm'); 

    
    boutonfiltre.forEach(button => {
        button.addEventListener('click', () => {
            const genreSelectionner = button.getAttribute('data-genre');
            
            
            carteFilm.forEach(card => {
                const carteGenresAttribuer = card.getAttribute('data-genre');

let cartesGenres = carteGenresAttribuer.split(',');//verifie et extrait le genre de chaque cartes

if (genreSelectionner !== 'all') {//masquage carte sans genre
    if (cartesGenres.includes(genreSelectionner)) {//affichage par defaut
        card.style.display = 'block'; 
    } else {
        card.style.display = 'none';  
    }
} 
else {//affichage en fonction du bouton filtre cliquer 
    if (cartesGenres.includes("27")) {
        card.style.display = 'block';
    } else if (cartesGenres.includes("16")) {//affichage des cartes en fonction de leur code de genre
        card.style.display = 'block';
    } else if (cartesGenres.includes("14")) {
        card.style.display = 'block';
    } else if (cartesGenres.includes("53")) {
        card.style.display = 'block';
    } else if (cartesGenres.includes("18")) {
        card.style.display = 'block';
    } else if (cartesGenres.includes("878")) {
        card.style.display = 'block';
    } else if (cartesGenres.includes("28")) {
        card.style.display = 'block';
    } else {
        card.style.display = 'none';
    }
}
            });
        });
    });
}
