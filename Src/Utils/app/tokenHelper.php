<?php
    require_once 'httpHelper.php';

    //Coleta token do Usuário
    function getToken() {
        return isset($_SESSION['token']) ? $_SESSION['token'] : null;
    }

    //Gera token para o Usuário
    function tokenGenerate($tamanho = 32) {
        return bin2hex(random_bytes($tamanho));
    }

    //Válida token do Usuário
    function getUserByToken($conn) {
        $token = getToken();

        $result = mysqli_query($conn, "SELECT * FROM usuario WHERE token='$token");
        if (mysqli_num_rows($result) > 0) {
            return true;
        }

        header('Location: /caminho/login.html?login=erro');
    }
?>