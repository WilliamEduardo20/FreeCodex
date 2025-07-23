export function menuHor(bool) {
    if (bool == false) {
        //Coleta elementos
        const lista = document.querySelectorAll(".lista");

        //Ativa eles
        function ativaLink() {
            for (let i of lista) {
                i.classList.remove("ativo");
            }
            this.classList.add("ativo");
        }

        //Aplica configuração
        for (let i of lista) {
            i.addEventListener('click', ativaLink);
        }
    }
}