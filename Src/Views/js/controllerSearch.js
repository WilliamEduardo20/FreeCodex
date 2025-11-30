// controllerSearch.js - VERSÃO CORRIGIDA E MELHORADA

const input = document.getElementById('pesquisa');
const btnLimpar = document.getElementById('btn-apagar');
const btnVerMais = document.getElementById('btn-ver-mais');
const container = document.getElementById('container-cards');

let allCards = [];         // Todos os cards da pesquisa atual
let currentIndex = 0;      // Quantos já foram exibidos
const CARDS_POR_CLIQUE = 6;

btnLimpar.addEventListener('click', () => {
    input.value = '';
    input.focus();
});

// Pesquisa ao pressionar Enter
input.addEventListener('keyup', async function(e) {
    if (e.key === 'Enter') {
        const termo = this.value.trim();

        if (termo === '') {
            location.reload();
            return;
        }

        // Resetar estado da pesquisa
        allCards = [];
        currentIndex = 0;
        container.innerHTML = '';
        btnVerMais.style.display = 'none'; // esconde temporariamente

        const dados = new URLSearchParams();
        const isHome = window.location.href.includes('index.html') || window.location.pathname === '/';
        dados.append('typeSearch', isHome ? 'publica' : 'privada');
        dados.append('text', termo);

        try {
            const response = await fetch('http://localhost/FreeCodex/Src/Services/capi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: dados
            });

            const result = await response.json();

            if (result.success && result.data.length > 0) {
                allCards = result.data;
                currentIndex = 0;

                // Mostra os primeiros 6
                carregarMaisCards();

                // Mostra botão "Ver mais" se tiver mais cards
                if (allCards.length > CARDS_POR_CLIQUE) {
                    btnVerMais.style.display = 'block';
                }
            } else {
                container.innerHTML = '<p class="sem-resultado">Nenhum resultado encontrado.</p>';
                btnVerMais.style.display = 'none';
            }
        } catch (err) {
            console.error('Erro na pesquisa:', err);
            container.innerHTML = '<p class="sem-resultado">Erro ao buscar. Tente novamente.</p>';
        }
    }
});

// Função reutilizada tanto na pesquisa quanto no "Ver mais"
function carregarMaisCards() {
    const proximosCards = allCards.slice(currentIndex, currentIndex + CARDS_POR_CLIQUE);
    
    if (proximosCards.length > 0) {
        renderizarCards(proximosCards);
        currentIndex += proximosCards.length;

        // Esconde o botão se não tiver mais
        if (currentIndex >= allCards.length) {
            btnVerMais.style.display = 'none';
        }
    }
}

// Botão "Ver mais" agora funciona tanto na home quanto na pesquisa
btnVerMais.addEventListener('click', carregarMaisCards);

// Função de renderização (igual à do controllerCards.js, mas adaptada)
function renderizarCards(data) {
    const fragment = document.createDocumentFragment();

    data.forEach(item => {
        const {
            id,
            tipo: type,
            categoria,
            lang1: LinguagemI,
            lang2: LinguagemII = '',
            codigo = '',
            download_link = null,
            preview: caminho = 'default-preview.jpg'
        } = item;

        const previewUrl = caminho ? `./Src/Public/${type}/${id}.html` : '#';
        const favoritoId = `${type}-${id}`;

        const botoes = {
            copiar: `
                <i class="bi bi-browser-chrome" onclick="location.href='${previewUrl}'" title="Ver preview"></i>
                <i class="bi bi-copy copiar-btn" onclick="copiar(this)" data-texto="${escapeAttribute(codigo)}" title="Copiar código"></i>
            `,
            baixar: `
                <i class="bi bi-browser-chrome" onclick="location.href='${previewUrl}'" title="Ver preview"></i>
                <i class="bi bi-file-earmark-arrow-down download-btn" onclick="location.href='${download_link}'" title="Baixar projeto"></i>
            `
        };

        const acoesHTML = type === 'copiar' ? botoes.copiar : botoes.baixar;

        // Verifica se está favoritado (via API ou localStorage - aqui vamos usar uma lógica simples por enquanto)
        // Se quiser sincronizar com o banco, vamos melhorar depois
        const favoritosSalvos = JSON.parse(localStorage.getItem('favoritos_temp') || '[]');
        const estaFavoritado = favoritosSalvos.includes(favoritoId);
        const classeFavorito = estaFavoritado ? 'favorito-ativo' : '';

        const cardHTML = `
            <div class="componente">
                <img class="componente-imagem" src="${escapeHtml(caminho)}" alt="Preview de ${escapeHtml(categoria)}" loading="lazy">
                <div class="componente-info">
                    <div class="componente-tipos">
                        <span class="componente-tipo">${escapeHtml(categoria)}</span>
                        <span class="componente-tipo">${escapeHtml(LinguagemI)}</span>
                        ${LinguagemII ? `<span class="componente-tipo">${escapeHtml(LinguagemII)}</span>` : ''}
                    </div>
                    <div class="componente-opcoes">
                        ${acoesHTML}
                        <button id="${favoritoId}" class="componente-btn ${classeFavorito}" onclick="favoritar(this)" title="Favoritar"></button>
                    </div>
                </div>
            </div>
        `;

        const div = document.createElement('div');
        div.innerHTML = cardHTML;
        fragment.appendChild(div.firstElementChild);
    });

    container.appendChild(fragment);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function escapeAttribute(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '')
        .replace(/</g, '<')
        .replace(/>/g, '>');
}