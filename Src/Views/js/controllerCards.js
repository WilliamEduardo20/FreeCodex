// controllerCards.js - APENAS PARA A HOME (carregamento inicial

let initialLoaded = false;

async function carregarInicial() {
    if (initialLoaded) return;
    
    const response = await fetch('http://localhost/FreeCodex/Src/Utils/controllerCards.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'carregaX=8'
    });

    const result = await response.json();
    
    if (result.success) {
        // Usa a mesma função do controllerSearch.js
        window.allCardsPesquisa = result.data; // disponibiliza globalmente
        window.currentIndexPesquisa = 0;

        const primeiros = result.data.slice(0, 6);
        window.renderizarCardsInicial?.(primeiros); // chama função do outro arquivo

        // Mostra botão se tiver mais
        if (result.data.length > 6) {
            document.getElementById('btn-ver-mais').style.display = 'block';
        }
    }
    initialLoaded = true;
}

carregarInicial();