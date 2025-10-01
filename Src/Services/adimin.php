<?php
    require_once('../Utils/app/httpHelper.php');
    require_once('../Utils/app/tokenHelper.php');
    require_once('../Config/conection.php');
    header('Content-Type: application/json; charset=UTF-8');

    switch ($method) {
        case 'GET':
            # code...
            break;
        
        case 'POST':
            # code...
            break;

        case 'PUT':
            # code...
            break;

        case 'DELETE':
            # code...
            break;

        default:
            sendResponse(405, ['massage' => 'Método HTTP não suportado']);
            break;
    }
?>