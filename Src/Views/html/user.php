<?php
    session_start();
    require_once('../../Utils/verifyAccess.php');
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
    <link rel="stylesheet" href="../css/user.css">
</head>

<body>
    <main class="esp-lat-ext">
        <section class="container pad30">
            <div class="perfil">
                <div class="foto-perfil">
                    <img src="<?= $_SESSION['imagem'] ?>" alt="">
                    <i class="bi bi-camera" id="alterarImagem"></i>
                </div>
                <button onclick="window.location.href='/freecodex2/Src/Utils/logoff.php'">Deslogar</button>
                <a id="deletarConta" href="javascript:void(0)">Delete a sua conta</a>
            </div>
            <div class="linha"></div>
            <div class="info">
                <form class="form">
                    <label for="email">Nome</label>
                    <input type="text" name="nome" id="nome" placeholder="Altere seu nome" value="<?= $_SESSION['nome'] ?>" disabled>
                    <a href="#" id="alterarNome">Alterar</a>
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Altere seu e-mail" value="<?= $_SESSION['email'] ?>" disabled>
                    <a href="#" id="alterarEmail">Alterar</a>
                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" id="telefone" placeholder="Altere seu telefone" value="<?= $_SESSION['telefone'] ?>" disabled>
                    <a href="#" id="alterarTelefone">Alterar</a>
                    <label for="password">Senha</label>
                    <input type="password" name="senha" id="password" placeholder="Altere sua senha" value="********" disabled>
                    <input type="hidden" name="senha_real" id="senha_real" value="<?= $_SESSION['senha'] ?>">
                    <a href="#" id="alterarSenha">Alterar</a>
                </form>
            </div>
        </section>
    </main>

    <!-- Modal para Alterar Imagem -->
    <div class="modal-container" id="modalImagem">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <span class="fechar-modal" data-modal="modalImagem">&times;</span>
                <h2>Alterar Imagem de Perfil</h2>
            </div>
            <div class="modal-corpo">
                <form id="formAlterarImagem" action="../../Utils/verifyChange.php" method="post">
                    <input type="hidden" name="change" value="updateImagem">
                    <label for="urlImagem">URL da Nova Imagem:</label>
                    <input type="text" id="urlImagem" name="urlImagem" placeholder="https://exemplo.com/imagem.jpg" required>
                    <button type="submit">Alterar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Nome -->
    <div class="modal-container" id="modalNome">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <span class="fechar-modal" data-modal="modalNome">&times;</span>
                <h2>Alterar Nome</h2>
            </div>
            <div class="modal-corpo">
                <form id="formAlterarNome" action="../../Utils/verifyChange.php" method="post">
                    <input type="hidden" name="change" value="updateNome">
                    <label for="novoNome">Novo Nome:</label>
                    <input type="text" id="novoNome" name="novoNome" placeholder="Digite o novo nome" required>
                    <button type="submit">Alterar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Senha -->
    <div class="modal-container" id="modalSenha">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <span class="fechar-modal" data-modal="modalSenha">&times;</span>
                <h2>Alterar Senha</h2>
            </div>
            <div class="modal-corpo">
                <form id="formAlterarSenha" action="../../Utils/verifyChange.php" method="post">
                    <input type="hidden" name="change" value="updateSenha">
                    <label for="senhaAtual">Senha Atual:</label>
                    <input type="password" id="senhaAtual" name="senhaAtual" placeholder="Digite a senha atual" required>
                    <label for="novaSenha">Nova Senha:</label>
                    <input type="password" id="novaSenha" name="novaSenha" placeholder="Digite a nova senha" required>
                    <label for="confirmarSenha">Confirmar Nova Senha:</label>
                    <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Confirme a nova senha" required>
                    <p id="mensagemSenha" style="margin-bottom: 10px;"></p>
                    <button type="submit">Alterar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar E-mail -->
    <div class="modal-container" id="modalEmail">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <span class="fechar-modal" data-modal="modalEmail">&times;</span>
                <h2>Alterar E-mail</h2>
            </div>
            <div class="modal-corpo">
                <form id="formAlterarEmail" action="../../Utils/verifyChange.php" method="post">
                    <input type="hidden" name="change" value="updateEmail">
                    <label for="novoEmail">Novo E-mail:</label>
                    <input type="email" id="novoEmail" name="novoEmail" placeholder="Digite o novo e-mail" required>
                    <label for="senhaAtual">Senha Atual:</label>
                    <input type="password" name="senhaAtual" placeholder="Digite a senha atual" required>
                    <button type="submit">Alterar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Telefone -->
    <div class="modal-container" id="modalTelefone">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <span class="fechar-modal" data-modal="modalTelefone">&times;</span>
                <h2>Alterar Telefone</h2>
            </div>
            <div class="modal-corpo">
                <form id="formAlterarTelefone" action="../../Utils/verifyChange.php" method="post">
                    <input type="hidden" name="change" value="updateTelefone">
                    <label for="novoTelefone">Novo Telefone:</label>
                    <input type="text" id="novoTelefone" name="novoTelefone" placeholder="Digite o novo telefone" required>
                    <label for="senhaAtual">Senha Atual:</label>
                    <input type="password" name="senhaAtual" placeholder="Digite a senha atual" required>
                    <button type="submit">Alterar</button>
                </form>
            </div>
        </div>
    </div>

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
                    <a href="./fav.html">
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
                <li class="item-menu ativo">
                    <a href="#">
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
                    <a href="./fav.html">
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
                <li class="lista ativo">
                    <a href="#">
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
    <script type="module" src="../js/modal.js"></script>
    <script type="module" src="../js/request.js"></script>
</body>
</html>