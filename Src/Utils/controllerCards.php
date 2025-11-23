<?php
    require_once __DIR__ . '/../Config/conection.php';
    require_once('../Utils/app/httpHelper.php');

    // Define cabeçalhos
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, PUT');
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
        
        case 'PATCH':
            $data = [];

            $query1 = "SELECT 
                            'baixar' AS tipo_card,
                            cb.ID AS codigo_id,
                            cat.Nome AS categoria,
                            l1.Nome AS linguagem1,
                            l2.Nome AS linguagem2,
                            cb.caminho AS imagem_preview,
                            cb.Link AS link_download
                        FROM baiusuario bu
                        JOIN codbaixar cb     ON bu.CodigoSalvo = cb.ID
                        JOIN categorias cat   ON cb.Categoria = cat.ID
                        JOIN linguagens l1    ON cb.LinguagemI = l1.ID
                        JOIN linguagens l2    ON cb.LinguagemII = l2.ID
                        WHERE bu.UsuarioID = ?   -- <<-- aqui o ID do usuário logado
                        AND (
                                LOWER(l1.Nome) = LOWER(?) 
                            OR LOWER(l2.Nome) = LOWER(?) 
                            OR LOWER(cat.Nome) IN (LOWER(?), LOWER(?))
                        ) UNION ALL

                        SELECT 
                            'copiar' AS tipo_card,
                            cc.ID AS codigo_id,
                            cat.Nome AS categoria,
                            l1.Nome AS linguagem1,
                            l2.Nome AS linguagem2,
                            cc.caminho AS imagem_preview,
                            NULL AS link_download
                        FROM copusuario cu
                        JOIN codcopiar cc     ON cu.CodigoSalvo = cc.ID
                        JOIN categorias cat   ON cc.Categoria = cat.ID
                        JOIN linguagens l1    ON cc.LinguagemI = l1.ID
                        JOIN linguagens l2    ON cc.LinguagemII = l2.ID
                        WHERE cu.UsuarioID = ?   -- <<-- mesma coisa aqui
                        AND (
                                LOWER(l1.Nome) = LOWER(?) 
                            OR LOWER(l2.Nome) = LOWER(?) 
                            OR LOWER(cat.Nome) IN (LOWER(?), LOWER(?))
                        ) ORDER BY categoria, linguagem1, codigo_id;";
            
            $query2 = "(SELECT 
                            'copiar' AS tipo,
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
                        WHERE cat.Nome IN (?, ?)                                   -- 'tela', 'componente'
                        AND (
                                (LOWER(l1.Nome) = LOWER(?) AND LOWER(l2.Nome) = LOWER(?)) OR   -- ex: CSS → HTML
                                (LOWER(l1.Nome) = LOWER(?) AND LOWER(l2.Nome) = LOWER(?))     -- ex: HTML → CSS
                            )
                        )

                        UNION ALL

                        (SELECT 
                            'baixar' AS tipo,
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
                        WHERE cat.Nome IN (?, ?)                                   -- 'tela', 'componente'
                        AND (
                                (LOWER(l1.Nome) = LOWER(?) AND LOWER(l2.Nome) = LOWER(?)) OR
                                (LOWER(l1.Nome) = LOWER(?) AND LOWER(l2.Nome) = LOWER(?))
                            )
                        )

                        ORDER BY categoria, id;";
            
            $typeSearch = request('tipoPesquisa');
            $sql = $typeSearch == 'privado' ? $query1 : $query2;
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }

            $itens = $data;
            echo json_encode([
                'success' => true,
                'data' => $itens,
                'total' => count($itens)
            ]);
            break;
    }
?>