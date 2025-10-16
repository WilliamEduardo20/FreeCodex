export function modal() {
    // Modal de vídeo original
    const modalVideo = document.getElementById('meuModal');
    const botaoAbrirVideo = document.getElementById('abrirModal');

    // Verifica se o modal de vídeo existe
    if (modalVideo) {
        const fecharVideo = modalVideo.querySelector('.fechar-modal');
        
        if (botaoAbrirVideo) {
            botaoAbrirVideo.onclick = function() {
                modalVideo.style.display = 'block';
            };
        }

        if (fecharVideo) {
            fecharVideo.onclick = function() {
                modalVideo.style.display = 'none';
            };
        }
    }

    // Função genérica para fechar modais ao clicar fora
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-container')) {
            event.target.style.display = 'none';
        }
    };

    // Função para formatar telefone
    function formatarTelefone(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor.length > 11) valor = valor.slice(0, 11);

        if (valor.length > 2) {
            valor = `(${valor.slice(0, 2)}) ${valor.slice(2)}`;
        }
        if (valor.length > 8) {
            valor = `${valor.slice(0, 9)}-${valor.slice(9)}`;
        }
        if (valor.length > 15) {
            valor = valor.slice(0, 14);
        }

        input.value = valor;
    }

    // Novos modais para alterações
    const modals = [
        { id: 'modalNome', abrirId: 'alterarNome' },
        { id: 'modalSenha', abrirId: 'alterarSenha' },
        { id: 'modalEmail', abrirId: 'alterarEmail' },
        { id: 'modalTelefone', abrirId: 'alterarTelefone' }
    ];

    modals.forEach(m => {
        const modalElem = document.getElementById(m.id);
        const botaoAbrir = document.getElementById(m.abrirId);

        // Verifica se o modal e o botão de abrir existem
        if (modalElem && botaoAbrir) {
            const fecharModal = modalElem.querySelector('.fechar-modal');

            botaoAbrir.onclick = function(event) {
                event.preventDefault();
                modalElem.style.display = 'block';

                // Aplica formatação ao campo de telefone quando o modalTelefone é aberto
                if (m.id === 'modalTelefone') {
                    const telefoneInput = document.getElementById('novoTelefone');
                    if (telefoneInput) {
                        telefoneInput.addEventListener('input', () => formatarTelefone(telefoneInput));
                    }
                }
            };

            if (fecharModal) {
                fecharModal.onclick = function() {
                    modalElem.style.display = 'none';
                };
            }

            // Lógica para submissão dos forms
            const form = modalElem.querySelector('form');
            if (form) {
                form.onsubmit = function(event) {
                    event.preventDefault();
                    if (m.id === 'modalNome') {
                        const novoValor = document.getElementById('novoNome').value;
                        if (novoValor) {
                            document.getElementById('nome').value = novoValor;
                        }
                    } else if (m.id === 'modalSenha') {
                        const novaSenha = document.getElementById('novaSenha').value;
                        const confirmar = document.getElementById('confirmarSenha').value;
                        if (novaSenha && novaSenha === confirmar) {
                            document.getElementById('password').value = '********';
                        }
                    } else if (m.id === 'modalEmail') {
                        const novoValor = document.getElementById('novoEmail').value;
                        if (novoValor) {
                            document.getElementById('email').value = novoValor;
                        }
                    } else if (m.id === 'modalTelefone') {
                        const novoValor = document.getElementById('novoTelefone').value;
                        if (novoValor) {
                            document.getElementById('telefone').value = novoValor;
                        }
                    }
                    modalElem.style.display = 'none';
                };
            }
        }
    });
}