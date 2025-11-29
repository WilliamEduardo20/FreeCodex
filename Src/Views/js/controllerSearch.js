const input = document.getElementById('pesquisa');
const btnLimpar = document.getElementById('btn-apagar');
var allCards = [];

// Limpa o campo ao clicar no X
btnLimpar.addEventListener('click', function() {
    input.value = '';
    input.focus();
});

// Pesquisa ao pressionar Enter
input.addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const termo = this.value.trim();
        if (termo === '') {
            location.reload();
            return;
        } else {
            async function pesquisaCards() {
                const dados = new URLSearchParams();
                var typeSearch = (href === 'http://localhost/FreeCodex/index.html' || href.startsWith('http://localhost/FreeCodex/')) ? 'publica' : 'privada';
                dados.append('typeSearch', typeSearch);
                dados.append('text', termo);
            
                try {
                    const response = await fetch('http://localhost/FreeCodex/Src/Services/capi.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: dados
                    });
            
                    const data = await response.json();
                    console.log('Dados pesuisa:', data);
            
                    if (data.success) {
                        allCards = data.data;

                        // Renderiza APENAS OS PRIMEIROS 6
                        const carrega2 = data.data.slice(0, 6);
                        renderizarCards(carrega2);
                    }
                } catch (err) { }
            }
            pesquisaCards();
        }
    }
});

function renderizarCards(data) {
    const container = document.getElementById('container-cards');
    container.innerHTML = ''; // limpa tudo na pesquisa

    const fragment = document.createDocumentFragment();

    data.forEach(item => {
        const {
            id,
            tipo: type,           // "tipo" → vira "type"
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
                <i class="bi bi-copy copiar-btn" 
                   onclick="copiar(this)" 
                   data-texto="${escapeAttribute(codigo)}" 
                   title="Copiar código"></i>
            `,
            baixar: `
                <i class="bi bi-browser-chrome" onclick="location.href='${previewUrl}'" title="Ver preview"></i>
                <i class="bi bi-file-earmark-arrow-down download-btn" 
                   onclick="location.href='${download_link}'" 
                   title="Baixar projeto"></i>
            `
        };

        // capi.php retorna "copiar" ou "baixar" em minúsculo
        const acoesHTML = type === 'copiar' ? botoes.copiar : 
                         type === 'baixar' ? botoes.baixar : botoes.copiar;

        const cardHTML = `
            <div class="componente">
                <img class="componente-imagem" 
                     src="${escapeHtml(caminho)}" 
                     alt="Preview de ${escapeHtml(categoria)}" 
                     loading="lazy">
                <div class="componente-info">
                    <div class="componente-tipos">
                        <span class="componente-tipo">${escapeHtml(categoria)}</span>
                        <span class="componente-tipo">${escapeHtml(LinguagemI)}</span>
                        ${LinguagemII ? `<span class="componente-tipo">${escapeHtml(LinguagemII)}</span>` : ''}
                    </div>
                    <div class="componente-opcoes">
                        ${acoesHTML}
                        <button id="${favoritoId}" class="componente-btn ${(window.location.href == 'http://localhost/FreeCodex/index.html' ? '' : 'favorito-ativo')}" onclick="favoritar(this)" title="Favoritar"></button>
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