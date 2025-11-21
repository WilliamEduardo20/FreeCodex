function modal() {
    // Função genérica para fechar modais ao clicar fora
    window.onclick = function (event) {
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

    // Função para validar senhas
    function validarSenhas() {
        const novaSenha = document.getElementById('novaSenha');
        const confirmarSenha = document.getElementById('confirmarSenha');
        const mensagem = document.getElementById('mensagemSenha');
        const submitButton = document.querySelector('#formAlterarSenha button[type="submit"]');

        if (novaSenha.value !== confirmarSenha.value) {
            mensagem.textContent = 'Senhas diferentes';
            mensagem.style.color = 'red';
            mensagem.style.fontSize = '20px';
            mensagem.style.fontWeight = '500';
            mensagem.style.textAlign = 'center';
            submitButton.disabled = true;
        } else {
            mensagem.textContent = 'Senhas iguais!';
            mensagem.style.color = 'green';
            mensagem.style.fontSize = '20px';
            mensagem.style.fontWeight = '500';
            mensagem.style.textAlign = 'center';
            submitButton.disabled = false;
        }
    }

    // Função para trocar imagem (executada no lado do cliente antes do envio do formulário)
    function trocarImagem() {
        const url = document.getElementById('urlImagem').value;
        if (url) {
            document.getElementById('minhaImagem').src = url;
        } else {
            alert('Por favor, insira uma URL válida.');
        }
    }

    // Lista de modais, incluindo o modal de imagem
    const modals = [
        { id: 'modalNome', abrirId: 'alterarNome' },
        { id: 'modalSenha', abrirId: 'alterarSenha' },
        { id: 'modalEmail', abrirId: 'alterarEmail' },
        { id: 'modalTelefone', abrirId: 'alterarTelefone' },
        { id: 'modalImagem', abrirId: 'alterarImagem' }
    ];

    modals.forEach(m => {
        const modalElem = document.getElementById(m.id);
        const botaoAbrir = document.getElementById(m.abrirId);

        // Verifica se o modal e o botão de abrir existem
        if (modalElem && botaoAbrir) {
            const fecharModal = modalElem.querySelector('.fechar-modal');

            botaoAbrir.onclick = function (event) {
                event.preventDefault();
                modalElem.style.display = 'block';

                // Aplica formatação ao campo de telefone quando o modalTelefone é aberto
                if (m.id === 'modalTelefone') {
                    const telefoneInput = document.getElementById('novoTelefone');
                    if (telefoneInput) {
                        telefoneInput.addEventListener('input', () => formatarTelefone(telefoneInput));
                    }
                }

                // Aplica validação de senhas quando o modalSenha é aberto
                if (m.id === 'modalSenha') {
                    const novaSenha = document.getElementById('novaSenha');
                    const confirmarSenha = document.getElementById('confirmarSenha');
                    if (novaSenha && confirmarSenha) {
                        novaSenha.addEventListener('input', validarSenhas);
                        confirmarSenha.addEventListener('input', validarSenhas);
                    }
                }

                // Aplica pré-visualização da imagem quando o modalImagem é aberto
                if (m.id === 'modalImagem') {
                    const urlImagemInput = document.getElementById('urlImagem');
                    if (urlImagemInput) {
                        urlImagemInput.addEventListener('input', trocarImagem);
                    }
                }
            };

            if (fecharModal) {
                fecharModal.onclick = function () {
                    modalElem.style.display = 'none';
                };
            }
        }
    });
}
modal();