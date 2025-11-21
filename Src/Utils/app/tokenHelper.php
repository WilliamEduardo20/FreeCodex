<?php
    require_once __DIR__ . '/httpHelper.php';

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

        $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE Token='$token'");
        if (mysqli_num_rows($result) > 0) {
            return true;
        }

        header('Location: /freecodex2/Src/Views/html/login.html?login=erro');
        exit;
    }
?>