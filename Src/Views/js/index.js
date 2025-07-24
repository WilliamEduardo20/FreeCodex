import { menuHor } from "../js/menuHor.js";
import { menuLat } from "../js/menuLat.js";

const pagina = document.body;
function atualizarMenus() {
    const bool = pagina.offsetWidth > 730;
    menuHor(bool);
    menuLat(bool);
}

window.addEventListener('resize', atualizarMenus);
window.addEventListener('load', atualizarMenus);