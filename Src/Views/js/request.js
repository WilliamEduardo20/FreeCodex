export function putRequest() {
    const dados = new URLSearchParams();
    dados.append('nome', document.getElementById('nome').value);
    dados.append('email', document.getElementById('email').value);
    dados.append('telefone', document.getElementById('telefone').value);
    dados.append('senha', document.getElementById('senha_real').value);
    
    fetch('http://localhost/FreeCodex/Src/Services/api.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: dados,
    })
}

export function deleteRequest() {
    fetch('http://localhost/FreeCodex/Src/Services/api.php', {
        method: 'DELETE'
    }).then(data => {
        window.location.href = '/FreeCodex/index.html';
    })
}

document.querySelector('#deletarConta').addEventListener('click', (event) => {
    event.preventDefault();
    deleteRequest();
});