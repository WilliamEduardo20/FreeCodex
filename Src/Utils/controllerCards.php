<?php
    require_once __DIR__ . '/../Config/conection.php';
    require_once('../Utils/app/httpHelper.php');

    // Define cabeçalhos
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, PUT, PATCH');
    header('Access-Control-Allow-Headers: Content-Type');

    session_start();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $data1 = $data2 = [];

            // Query para códigos de download
            $query1 = "SELECT 
                            cb.ID,
                            'download' AS type,
                            c.Nome AS Categoria,
                            l1.Nome AS LinguagemI,
                            l2.Nome AS LinguagemII,
                            cb.Link AS download_link,
                            cb.caminho
                        FROM codbaixar cb
                        JOIN categorias c  ON cb.Categoria = c.ID
                        JOIN linguagens l1 ON cb.LinguagemI = l1.ID
                        JOIN linguagens l2 ON cb.LinguagemII = l2.ID
                        WHERE cb.ID BETWEEN ? AND ?";

            // Query para códigos de copiar
            $query2 = "SELECT
                            cc.ID,
                            'copy' AS type,
                            c.Nome AS Categoria,
                            l1.Nome AS LinguagemI,
                            l2.Nome AS LinguagemII,
                            cc.Codigo AS codigo,
                            cc.caminho
                        FROM codcopiar cc
                        JOIN categorias c  ON cc.Categoria = c.ID
                        JOIN linguagens l1 ON cc.LinguagemI = l1.ID
                        JOIN linguagens l2 ON cc.LinguagemII = l2.ID
                        WHERE cc.ID BETWEEN ? AND ?";

            $y = request('carregaX');
            $x = max(1, $y - 9);

            // Executa query 1
            if ($stmt = mysqli_prepare($conn, $query1)) {
                mysqli_stmt_bind_param($stmt, 'ii', $x, $y);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data1 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }

            // Executa query 2
            if ($stmt = mysqli_prepare($conn, $query2)) {
                mysqli_stmt_bind_param($stmt, 'ii', $x, $y);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data2 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }

            $itens = array_merge($data1, $data2);
            shuffle($itens); // se quiser aleatório

            echo json_encode([
                'success' => true,
                'data' => $itens,
                'total' => count($itens)
            ]);
            break;
        
        case 'PUT':
            $data1 = $data2 = [];

            // Query para códigos de download
            $query1 = "SELECT 
                            cb.ID,
                            'download' AS type,
                            c.Nome AS Categoria,
                            l1.Nome AS LinguagemI,
                            l2.Nome AS LinguagemII,
                            cb.Link AS download_link,
                            cb.caminho
                        FROM baiusuario bu
                        JOIN codbaixar cb   ON cb.ID = bu.CodigoSalvo
                        JOIN categorias c   ON cb.Categoria = c.ID
                        JOIN linguagens l1  ON cb.LinguagemI = l1.ID
                        JOIN linguagens l2  ON cb.LinguagemII = l2.ID
                        WHERE bu.UsuarioID = ?";

            // Query para códigos de copiar
            $query2 = "SELECT 
                            cp.ID,
                            'copy' AS type,
                            c.Nome AS Categoria,
                            l1.Nome AS LinguagemI,
                            l2.Nome AS LinguagemII,
                            cp.Codigo,
                            cp.caminho
                        FROM copusuario cu
                        JOIN codcopiar cp  ON cp.ID = cu.CodigoSalvo
                        JOIN categorias c  ON cp.Categoria = c.ID
                        JOIN linguagens l1 ON cp.LinguagemI = l1.ID
                        JOIN linguagens l2 ON cp.LinguagemII = l2.ID
                        WHERE cu.UsuarioID = ?";

            // Executa query 1
            if ($stmt = mysqli_prepare($conn, $query1)) {
                mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data1 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }

            // Executa query 2
            if ($stmt = mysqli_prepare($conn, $query2)) {
                mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data2 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }

            $itens = array_merge($data1, $data2);
            echo json_encode([
                'success' => true,
                'data' => $itens,
                'total' => count($itens)
            ]);
            break;
    }
?>