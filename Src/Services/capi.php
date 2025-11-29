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
            $data = [];

            $query1 = "SELECT 
                        'download' AS tipo,
                        cb.ID AS id,
                        cat.Nome AS categoria,
                        l1.Nome AS lang1,
                        l2.Nome AS lang2,
                        NULL AS codigo,
                        cb.caminho AS preview,
                        cb.Link AS download_link
                    FROM baiusuario bu
                    JOIN codbaixar cb     ON bu.CodigoSalvo = cb.ID
                    JOIN categorias cat   ON cb.Categoria = cat.ID
                    JOIN linguagens l1    ON cb.LinguagemI = l1.ID
                    JOIN linguagens l2    ON cb.LinguagemII = l2.ID
                    WHERE bu.UsuarioID = ?
                    AND (LOWER(l1.Nome)  LIKE LOWER(?)
                        OR LOWER(l2.Nome)  LIKE LOWER(?)
                        OR LOWER(cat.Nome) LIKE LOWER(?))

                    UNION ALL

                    SELECT 
                        'copy' AS tipo,
                        cc.ID AS id,
                        cat.Nome AS categoria,
                        l1.Nome AS lang1,
                        l2.Nome AS lang2,
                        cc.Codigo AS codigo,
                        cc.caminho AS preview,
                        NULL AS download_link
                    FROM copusuario cu
                    JOIN codcopiar cc     ON cu.CodigoSalvo = cc.ID
                    JOIN categorias cat   ON cc.Categoria = cat.ID
                    JOIN linguagens l1    ON cc.LinguagemI = l1.ID
                    JOIN linguagens l2    ON cc.LinguagemII = l2.ID
                    WHERE cu.UsuarioID = ?
                    AND (LOWER(l1.Nome)  LIKE LOWER(?)
                        OR LOWER(l2.Nome)  LIKE LOWER(?)
                        OR LOWER(cat.Nome) LIKE LOWER(?))";
            
            $query2 = "SELECT 
                            'copy' AS tipo,
                            c.ID AS id,
                            cat.Nome AS categoria,
                            l1.Nome AS lang1,
                            l2.Nome AS lang2,
                            c.Codigo AS codigo,
                            c.caminho AS preview,
                            NULL AS download_link
                        FROM codcopiar c
                        JOIN categorias cat   ON c.Categoria = cat.ID
                        JOIN linguagens l1    ON c.LinguagemI = l1.ID
                        JOIN linguagens l2    ON c.LinguagemII = l2.ID
                        WHERE LOWER(cat.Nome) LIKE LOWER(?)                                   
                        OR LOWER(l1.Nome) LIKE LOWER(?) 
                        OR LOWER(l2.Nome) LIKE LOWER(?)
                        
                        UNION ALL
                        
                        SELECT 
                            'download' AS tipo,
                            b.ID AS id,
                            cat.Nome AS categoria,
                            l1.Nome AS lang1,
                            l2.Nome AS lang2,
                            NULL AS codigo,
                            b.caminho AS preview,
                            b.Link AS download_link
                        FROM codbaixar b
                        JOIN categorias cat   ON b.Categoria = cat.ID
                        JOIN linguagens l1    ON b.LinguagemI = l1.ID
                        JOIN linguagens l2    ON b.LinguagemII = l2.ID
                        WHERE LOWER(cat.Nome) LIKE LOWER(?)
                        OR LOWER(l1.Nome) LIKE LOWER(?) 
                        OR LOWER(l2.Nome) LIKE LOWER(?)
                        
                        ORDER BY categoria, id;";
            
            $typeSearch = request('typeSearch');
            $text = "%" . request("text") . "%";

            if ($typeSearch === 'publica') {
                $sql = $query2;
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $text, $text, $text, $text, $text, $text);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    mysqli_stmt_close($stmt);
                }
            } else {
                $usuarioId = $_SESSION['id'];
                $sql = $query1;
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "isssisss", 
                        $usuarioId,
                        $text, $text, $text,
                        $usuarioId,
                        $text, $text, $text
                    );
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    mysqli_stmt_close($stmt);
                }
            }

            echo json_encode([
                'success' => true,
                'data' => $data,
                'total' => count($data)
            ]);
            break;
    }
?>