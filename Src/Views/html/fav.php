<?php
    session_start();
    require_once('../../Utils/verifyAccess.php');
    require_once('../../Utils/controllerFav.php');

    initFavorites();
    if (isset($_GET['more'])) { // botão "VER MAIS"
        loadMoreFavorites();
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeCodex - Soluções Incríveis para Você</title>
    <meta name="description" content="">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="">
    <link rel="canonical" href="https://www.meusite.com.br">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/fav.css">
</head>
<body>
    <header></header>
    <main class="esp-lat-ext">
        <br>
        <div class="caixa-busca">
            <div class="lupa-buscar">
                <i class="bi bi-search"></i>
            </div>
            <div class="input-buscar">
                <input type="text" placeholder="O que você procura?" id="">
            </div>
            <div class="btn-fechar">
                <i class="bi bi-x"></i>
            </div>
        </div>
        <br>
        <section class="container mar-bott10">
            <?php renderFavorites(); ?>
        </section>
        <button class="btn-ver-mais mar-bott10" onclick="location.href='?more=1'">VER MAIS</button>
    </main>

    <footer></footer>

    <aside>
        <nav class="menu-lateral">
            <div class="btn-expandir">
                <div class="linhas" id="btn-exp">
                    <div class="linha-1"></div>
                    <div class="linha-2"></div>
                    <div class="linha-3"></div>
                </div>
            </div>
            <ul>
                <li class="item-menu">
                    <a href="../../../index.php">
                        <span class="icon">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <span class="txt-link">Home</span>
                    </a>
                </li>
                <li class="item-menu ativo">
                    <a href="#">
                        <span class="icon">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        <span class="txt-link">Favoritos</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="./faq.php">
                        <span class="icon">
                            <i class="bi bi-question-circle-fill"></i>
                        </span>
                        <span class="txt-link">FAQ</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="./user.php">
                        <span class="icon">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <span class="txt-link">Conta</span>
                    </a>
                </li>
            </ul>
        </nav>

        <nav class="navegacao">
            <ul>
                <li class="lista">
                    <a href="../../../index.php">
                        <span class="icone">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <span class="texto">Home</span>
                    </a>
                </li>
                <li class="lista ativo">
                    <a href="#">
                        <span class="icone">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        <span class="texto">Favoritos</span>
                    </a>
                </li>
                <li class="lista">
                    <a href="./faq.php">
                        <span class="icone">
                            <i class="bi bi-question-circle-fill"></i>
                        </span>
                        <span class="texto">FAQ</span>
                    </a>
                </li>
                <li class="lista">
                    <a href="./user.php">
                        <span class="icone">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <span class="texto">Conta</span>
                    </a>
                </li>
                <div class="indicador"></div>
            </ul>
        </nav>
    </aside>

    <script type="module" src="../js/index.js"></script>
</body>
</html>