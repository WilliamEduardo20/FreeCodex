let allCards = [];
localStorage['qtdTotal'] = 8;
localStorage['qtdAtual'] = 2;
async function carregarCards() {
    try {
        const response = await fetch('http://localhost/FreeCodex/Src/Utils/controllerCards.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        const data = await response.json();
        console.log('Dados recebidos:', data);

        if (data.success && data.data?.length > 0) {
            allCards = data.data;

            // Renderiza APENAS OS PRIMEIROS 4
            const carrega2 = data.data.slice(0, 4);
            renderizarCards(carrega2);
        }
    } catch (err) {
        console.error('Erro:', err);
    }
}
carregarCards();

function renderizarCards(data) {
    const container = document.getElementById('container-cards');

    // Limpa apenas na primeira carga
    if (!container.dataset.loaded) {
        container.innerHTML = '';
        container.dataset.loaded = 'true';
    }

    // Cria um fragmento para melhor performance (evita múltiplos reflows)
    const fragment = document.createDocumentFragment();

    data.forEach(item => {
        const {
            ID: id,
            type,
            Categoria: categoria,
            LinguagemI: langI,
            LinguagemII: langII = '',
            codigo = '',
            download_link,
            caminho = 'default-preview' // fallback
        } = item;

        const previewUrl = caminho ? `./Src/Public/${type}/${id}.html` : '#';
        const favoritoId = `${type}-${id}`;
        const estaFavorito = localStorage.getItem(favoritoId) === 'true';
        const coracaoClasse = estaFavorito ? 'bi-heart-fill favorito' : 'bi-heart';

        // Botões de ação com base no tipo
        const botoes = {
            copy: `
                <i class="bi bi-browser-chrome" onclick="location.href='${previewUrl}'" title="Ver preview"></i>
                <i class="bi bi-copy copiar-btn" 
                   onclick="copiar(this)" 
                   data-texto="${escapeAttribute(codigo)}" 
                   title="Copiar código"></i>
            `,
            download: `
                <i class="bi bi-browser-chrome" onclick="location.href='${previewUrl}'" title="Ver preview"></i>
                <i class="bi bi-file-earmark-arrow-down download-btn" 
                   onclick="location.href='${download_link}'" 
                   title="Baixar projeto"></i>
            `
        };

        const acoesHTML = botoes[type] || botoes.copy; // fallback para copy se tipo inválido

        // Card completo (template literal limpo e seguro)
        const cardHTML = `
            <div class="componente" style="position:relative">
                <img class="componente-imagem" 
                     src="${escapeHtml(caminho)}" 
                     alt="Preview de ${escapeHtml(categoria)}" 
                     loading="lazy">
                     
                <div class="componente-info">
                    <div class="componente-tipos">
                        <span class="componente-tipo">${escapeHtml(categoria)}</span>
                        <span class="componente-tipo">${escapeHtml(langI)}</span>
                        ${langII ? `<span class="componente-tipo">${escapeHtml(langII)}</span>` : ''}
                    </div>
                    <div class="componente-opcoes">
                        ${acoesHTML}
                        <button id="${favoritoId}" class="componente-btn favorito-ativo" onclick="favoritar(this)" title="Favoritar"></button>
                    </div>
                </div>
            </div>
        `;

        // Cria elemento e adiciona ao fragmento
        const div = document.createElement('div');
        div.innerHTML = cardHTML;
        fragment.appendChild(div.firstElementChild);
    });

    container.appendChild(fragment);
}

document.getElementById('btn-ver-mais').addEventListener('click', ()=>{
    renderizarCards(allCards.slice(localStorage.getItem('qtdAtual'), localStorage.getItem('qtdAtual')+4));
    localStorage['qtdAtual'] = localStorage.getItem('qtdAtual') + 4;
});

// Função auxiliar para escapar HTML (segurança)
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function escapeAttribute(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}



function _defineFuncaoCopiar() {
    window.copiar = function (elemento) {
        let texto = elemento.getAttribute('data-texto');
        if (!texto) texto = (elemento.innerText || elemento.textContent).trim();

        navigator.clipboard.writeText(texto).then(() => {
            const corOriginal = elemento.style.color;
            const tituloOriginal = elemento.getAttribute('title') || 'Copiar';

            elemento.style.color = '#28a745';
            elemento.title = 'Copiado!';
            if (elemento.classList.contains('bi-copy')) {
                elemento.classList.replace('bi-copy', 'bi-check-lg');
            }

            setTimeout(() => {
                elemento.style.color = corOriginal;
                elemento.title = tituloOriginal;
                if (elemento.classList.contains('bi-check-lg')) {
                    elemento.classList.replace('bi-check-lg', 'bi-copy');
                }
            }, 1500);

        });
    };
}
_defineFuncaoCopiar();

function _defineFuncaoFavoritar() {
    window.favoritar = async function (elemento) {
        try {
            // 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO (caminho CORRETO!)
            const verifyRes = await fetch('http://localhost/FreeCodex/Src/Utils/auxAccess.php', {
                method: 'GET',
                credentials: 'include' 
            });

            const verifyData = await verifyRes.json();

            // Se não estiver logado
            console.log(verifyData);
            if (!verifyData.success || verifyData.data !== true) {
                window.location.href = './Src/Views/html/login.html';
                return;
            }

            // 2. Usuário logado → prossegue com favoritar
            const dados = new URLSearchParams();
            const id = elemento.id;
            dados.append('elemento', id);

            const favRes = await fetch('http://localhost/FreeCodex/Src/Services/apiCards.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: dados
            });

            elemento.classList.toggle("favorito-ativo");
            if (!elemento.classList.contains("favorito-ativo")) {
                fetch('http://localhost/FreeCodex/Src/Services/apiCards.php', {
                    method: 'PUT',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: dados
                });
            }
        } catch (err) { }
    };
}
_defineFuncaoFavoritar();