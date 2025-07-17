//Selecionar itens
var menuItem = document.querySelectorAll(".item-menu");

function selecionarLink() {
    menuItem.forEach((item) => {
        item.classList.remove("ativo");
    });
    this.classList.add("ativo");
}

menuItem.forEach((item) => {
    item.addEventListener('click', selecionarLink);
});

//Expandir Menu
var btnExp = document.getElementById("btn-exp");
var menuLat = document.querySelector(".menu-lateral");

btnExp.addEventListener('click', () => {
    menuLat.classList.toggle("expandir");
});