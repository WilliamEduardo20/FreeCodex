<?php
    require_once('../Utils/app/httpHelper.php');
    require_once('../Utils/app/tokenHelper.php');
    require_once('../Config/conection.php');
    
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, PUT');
    header('Access-Control-Allow-Headers: Content-Type');

    session_start();
    $method = $_SERVER['REQUEST_METHOD'];

    function separarTexto($texto) {
        if (preg_match('/^(download|copy)-(\d+)$/i', trim($texto), $matches)) {
            return [
                'nome'   => strtolower($matches[1]), // 'download' ou 'copy'
                'numero' => (int)$matches[2]
            ];
        }
        return false;
    }

    switch ($method) {
        case 'POST':
            $elemento = request('elemento');
            $result = separarTexto($elemento);
            $texto = $result['nome'];
            $num = $result['numero'];

            if ($texto == 'download') {
                $query = "INSERT INTO baiusuario (UsuarioID, CodigoSalvo) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $_SESSION['id'], $num);
                $stmt->execute();
            } else if ($texto == 'copy') {
                $query = "INSERT INTO copusuario (UsuarioID, CodigoSalvo) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $_SESSION['id'], $num);
                $stmt->execute();
            }
            break;

        case 'PUT':
            $elemento = request('elemento');
            $result = separarTexto($elemento);
            $texto = $result['nome'];
            $num = $result['numero'];

            if ($texto == 'download') {
                $query = "DELETE FROM baiusuario WHERE UsuarioID = ? AND CodigoSalvo = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $_SESSION['id'], $num);
                $stmt->execute();
            } else if ($texto == 'copy') {
                $query = "DELETE FROM copusuario WHERE UsuarioID = ? AND CodigoSalvo = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $_SESSION['id'], $num);
                $stmt->execute();
            }
            break;

        default:
            sendResponse(405, ['massage' => 'Método HTTP não suportado']);
            break;
    }
?>