<?php
    require_once __DIR__ . '/../Config/conection.php';

    // Define cabeçalhos
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

    session_start();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $logou = !isset($_SESSION['id']) ? false : true;

            echo json_encode([
                'success' => true,
                'data' => $logou
            ]);
            break;
    }
?>