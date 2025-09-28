import { menuHor } from "../js/menuHor.js";
import { menuLat } from "../js/menuLat.js";
import { modal } from "./modal.js";

const pagina = document.body;
function atualizarMenus() {
    const bool = pagina.offsetWidth > 730;
    menuHor(bool);
    menuLat(bool);
}

modal();

window.addEventListener('resize', atualizarMenus);
window.addEventListener('load', atualizarMenus);