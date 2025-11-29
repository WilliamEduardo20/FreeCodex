<?php
    require_once('../../Utils/controllerFAQ.php');
    session_start();

    start();

    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'next') nextPage();
        elseif ($_GET['action'] === 'prev') previousPage();
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
    <link rel="stylesheet" href="../css/faq.css">
</head>
<body>
    <header></header>
    <main class="esp-lat-ext faq">
        <div class="info">
            <img src="../../Assets/images/ilustracao.png" alt="">
            <div class="perguntas">
                <h1>FAQ</h1>
                <div class="separador">
                    <?php creatFAQ(); ?>
                </div>
                <div class="setas">
                    <a href="?action=prev" class="circle"><i class="bi bi-arrow-left"></i></a>
                    <span><?= $_SESSION['page']; ?></span>
                    <a href="?action=next" class="circle"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <img src="../../Assets/icons/logo.png" alt="Logo" class="logo">
        <div class="wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#0099ff" fill-opacity="1"
                    d="M0,256L80,224C160,192,320,128,480,133.3C640,139,800,213,960,245.3C1120,277,1280,267,1360,261.3L1440,256L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
                </path>
            </svg>
            <span class="wave-text">@codepiece</span>
        </div>

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
                    <a href="../../../index.html">
                        <span class="icon">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <span class="txt-link">Home</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="./fav.php">
                        <span class="icon">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        <span class="txt-link">Favoritos</span>
                    </a>
                </li>
                <li class="item-menu ativo">
                    <a href="#">
                        <span class="icon">
                            <i class="bi bi-question-circle-fill"></i>
                        </span>
                        <span class="txt-link">FAQ</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="./frames.html">
                        <span class="icon">
                            <i class="bi bi-link-45deg"></i>
                        </span>
                        <span class="txt-link">Framewoks</span>
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
                    <a href="../../../index.html">
                        <span class="icone">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <span class="texto">Home</span>
                    </a>
                </li>
                <li class="lista">
                    <a href="./fav.php">
                        <span class="icone">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        <span class="texto">Favoritos</span>
                    </a>
                </li>
                <li class="lista ativo">
                    <a href="#">
                        <span class="icone">
                            <i class="bi bi-question-circle-fill"></i>
                        </span>
                        <span class="texto">FAQ</span>
                    </a>
                </li>
                <li class="lista">
                    <a href="./frames.html">
                        <span class="icone">
                            <i class="bi bi-link-45deg"></i>
                        </span>
                        <span class="texto">Framewoks</span>
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