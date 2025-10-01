<?php
    //Enviar resposta para a página HTML
    function sendResponse($status, $data) {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    //Coleta dados do Usuário
    function request($field) {
        parse_str(file_get_contents('php://input'), $input);
        return $input[$field];
    }
?>