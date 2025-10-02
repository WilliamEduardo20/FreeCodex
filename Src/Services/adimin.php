<?php
    require_once('../Utils/app/httpHelper.php');
    require_once('../Utils/app/tokenHelper.php');
    require_once('../Config/conection.php');
    header('Content-Type: application/json; charset=UTF-8');

    switch ($method) {
        case 'GET':
            $tabela = request('tabela');

            if (empty($tabela)) {
                sendResponse(400, ['message' => 'Campo Obrigatório não preenchidos']);
                exit;
            }

            $query = "SELECT * FROM $tabela";
            $stmt = mysqli_prepare($conn, $query);

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            foreach ($result as $linha) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['email']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['telefone']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['data_nasc']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['senha']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['data_cad']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['token']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['status']) . "</td>";
                echo "</tr>";
            }
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