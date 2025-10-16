const indicador = document.querySelector(".indicador");
const input = document.querySelector("input[type='password']");
const fraco = document.querySelector(".fraco");
const medio = document.querySelector(".medio");
const forte = document.querySelector(".forte");
const text = document.querySelector(".text");
const showBtn = document.querySelector(".showBtn");
const telefone = document.getElementById("telefone");

// Regex
let regExMinuscula = /[a-z]/;
let regExMaiuscula = /[A-Z]/;
let regExNumero = /[\d]/;
let regExEspecial = /[!,@,#,$,%,^,&,*,?,_,~,\-,(,)]/;

function trigger() {
    const valor = input.value;

    if (valor != "") {
        indicador.style.display = "flex"; // mostra o indicador

        let no = 0;

        // Fraca: menos de 4 caracteres ou só letras minúsculas/números/especiais
        if (valor.length <= 3 && (valor.match(regExMinuscula) || valor.match(regExNumero) || valor.match(regExEspecial) || valor.match(regExMaiuscula))) {
            no = 1;
        }
        // Média: letras maiúsculas ou combinações de dois tipos (minúscula+numero, minúscula+especial, numero+especial)
        else if (valor.length >= 4 && (
            (valor.match(regExMaiuscula)) ||
            (valor.match(regExMinuscula) && valor.match(regExNumero)) ||
            (valor.match(regExMinuscula) && valor.match(regExEspecial)) ||
            (valor.match(regExNumero) && valor.match(regExEspecial))
        )) {
            no = 2;
        }
        // Forte: tem minúscula + maiúscula + número + especial e tamanho >= 6
        if (valor.length >= 6 && valor.match(regExMinuscula) && valor.match(regExMaiuscula) && valor.match(regExNumero) && valor.match(regExEspecial)) {
            no = 3;
        }

        // Aplica classes e texto
        if (no == 1) {
            fraco.classList.add("active");
            medio.classList.remove("active");
            forte.classList.remove("active");
            text.style.display = "block";
            text.textContent = "Sua senha está fraca";
            text.className = "text fraco";
        }
        else if (no == 2) {
            fraco.classList.remove("active");
            medio.classList.add("active");
            forte.classList.remove("active");
            text.style.display = "block";
            text.textContent = "Sua senha está média";
            text.className = "text medio";
        }
        else if (no == 3) {
            fraco.classList.remove("active");
            medio.classList.remove("active");
            forte.classList.add("active");
            text.style.display = "block";
            text.textContent = "Sua senha está forte";
            text.className = "text forte";
        }

        // Mostrar/ocultar senha
        showBtn.onclick = function () {
            if (input.type == "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }

    } else {
        // Esconde tudo se campo vazio
        fraco.classList.remove("active");
        medio.classList.remove("active");
        forte.classList.remove("active");
        text.style.display = "none";
    }

}

const senha = document.getElementById("password");
const confirmar = document.getElementById("confirme");
const msg = document.getElementById("mensagem");

confirmar.addEventListener("keyup", function () {
    if (confirmar.value === "") {
        msg.textContent = "";
    } else if (senha.value === confirmar.value) {
        msg.style.color = "green";
        msg.textContent = "Senhas iguais!";
    } else {
        msg.style.color = "red";
        msg.textContent = "Senhas diferentes";
    }
});

// Função para formatar o telefone no formato (XX) XXXXX-XXXX
export function formatarTelefone() {
    let valor = telefone.value.replace(/\D/g, ''); // Remove tudo que não é número
    if (valor.length > 11) valor = valor.slice(0, 11); // Limita a 11 dígitos

    if (valor.length > 2) {
        valor = `(${valor.slice(0, 2)}) ${valor.slice(2)}`;
    }
    if (valor.length > 8) {
        valor = `${valor.slice(0, 9)}-${valor.slice(9)}`;
    }
    if (valor.length > 15) {
        valor = valor.slice(0, 14); // Limita o formato completo
    }

    telefone.value = valor;
}

// Aplica a formatação ao digitar ou colar
telefone.addEventListener("input", formatarTelefone);