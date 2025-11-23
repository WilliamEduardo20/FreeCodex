const input = document.getElementById('pesquisa');
const btnLimpar = document.getElementById('btn-apagar');

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
            alert('Digite algo para pesquisar!');
        } else {
            alert(`Você pesquisou por: "${termo}"`);
            // Aqui você coloca sua função real de busca
            // fazerPesquisa(termo);
        }
    }
});