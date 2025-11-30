const text = "Bem-vindo ao meu site!";
const speed = 70;
let index = 0;

function type() {
  if (index < text.length) {
    document.getElementById("typing-text").textContent += text.charAt(index);
    index++;
    setTimeout(type, speed);
  }
}

type();
