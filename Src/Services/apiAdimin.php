<?php
    // adimin.php → API de Administração Completa
    require_once '../Config/conection.php';
    require_once '../Utils/app/httpHelper.php';
    require_once '../Utils/app/tokenHelper.php';
    header('Content-Type: application/json; charset=UTF-8');

    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    switch ($method) {

        // ================================== LISTAR TABELAS ==================================
        case 'GET':
            $tabela = $_GET['tabela'] ?? '';

            $tabelas_permitidas = ['usuarios', 'linguagens', 'categorias', 'perguntas', 'baiusuario', 'copusuario', 'codbaixar', 'codcopiar'];
            if (!in_array($tabela, $tabelas_permitidas)) {
                sendResponse(400, ['message' => 'Tabela não permitida ou inválida']);
            }

            $query = "SELECT * FROM $tabela ORDER BY ID DESC";
            $result = mysqli_query($conn, $query);

            $dados = [];
            while ($row = mysqli_fetch_assoc($result)) {
                // Remove campos sensíveis
                if ($tabela === 'usuarios') {
                    unset($row['Senha'], $row['Token']);
                }
                $dados[] = $row;
            }

            sendResponse(200, $dados);
            break;


        // ================================== CADASTRAR LINGUAGEM ==================================
        case 'POST':
            $acao = $input['acao'] ?? '';

            if ($acao === 'cadastrar_linguagem') {
                $nome = trim($input['nome'] ?? '');
                if (empty($nome)) {
                    sendResponse(400, ['message' => 'Nome da linguagem é obrigatório']);
                }

                $nome = mysqli_real_escape_string($conn, $nome);
                $query = "INSERT INTO linguagens (Nome) VALUES ('$nome')";
                
                if (mysqli_query($conn, $query)) {
                    sendResponse(200, ['message' => 'Linguagem cadastrada com sucesso!', 'id' => mysqli_insert_id($conn)]);
                } else {
                    sendResponse(500, ['message' => 'Erro ao cadastrar linguagem']);
                }
            }

            // ================================== CADASTRAR CATEGORIA ==================================
            elseif ($acao === 'cadastrar_categoria') {
                $nome = trim($input['nome'] ?? '');
                if (empty($nome)) {
                    sendResponse(400, ['message' => 'Nome da categoria é obrigatório']);
                }

                $nome = mysqli_real_escape_string($conn, $nome);
                $query = "INSERT INTO categorias (Nome) VALUES ('$nome')";
                
                if (mysqli_query($conn, $query)) {
                    sendResponse(200, ['message' => 'Categoria cadastrada com sucesso!', 'id' => mysqli_insert_id($conn)]);
                } else {
                    sendResponse(500, ['message' => 'Erro ao cadastrar categoria']);
                }
            }

            // ================================== CADASTRAR PERGUNTA ==================================
            elseif ($acao === 'cadastrar_pergunta') {
                $pergunta = trim($input['pergunta'] ?? '');
                $resposta = trim($input['resposta'] ?? '');

                if (empty($pergunta) || empty($resposta)) {
                    sendResponse(400, ['message' => 'Pergunta e resposta são obrigatórias']);
                }

                $pergunta = mysqli_real_escape_string($conn, $pergunta);
                $resposta = mysqli_real_escape_string($conn, $resposta);

                $query = "INSERT INTO perguntas (Pergunta, Resposta) VALUES ('$pergunta', '$resposta')";
                
                if (mysqli_query($conn, $query)) {
                    sendResponse(200, ['message' => 'Pergunta cadastrada com sucesso!', 'id' => mysqli_insert_id($conn)]);
                } else {
                    sendResponse(500, ['message' => 'Erro ao cadastrar pergunta']);
                }
            }

            elseif ($acao === 'editar_usuario') {
                $id = (int)($input['id'] ?? 0);
                $nome = trim($input['nome'] ?? '');
                $email = trim($input['email'] ?? '');
                $telefone = trim($input['telefone'] ?? '');
                $nascimento = $input['nascimento'] ?? '';
                $imagem = $input['imagem'] ?? '';
                $status = $input['status'] ?? 'i';

                if ($id <= 0 || empty($nome) || empty($email)) {
                    sendResponse(400, ['message' => 'Dados inválidos']);
                }

                $nome = mysqli_real_escape_string($conn, $nome);
                $email = mysqli_real_escape_string($conn, $email);
                $telefone = mysqli_real_escape_string($conn, $telefone);
                $imagem = mysqli_real_escape_string($conn, $imagem);
                $status = ($status === 'a') ? 'a' : 'i';

                $query = "UPDATE usuarios SET 
                            Nome = '$nome',
                            Email = '$email',
                            Telefone = '$telefone',
                            DataNasc = '$nascimento',
                            Imagem = '$imagem',
                            Status = '$status'
                        WHERE ID = $id";

                if (mysqli_query($conn, $query)) {
                    sendResponse(200, ['message' => 'Usuário atualizado com sucesso!']);
                } else {
                    sendResponse(500, ['message' => 'Erro ao atualizar usuário']);
                }
            }

            else {
                sendResponse(400, ['message' => 'Ação não reconhecida']);
            }
            break;


        // ================================== DELETAR (OPCIONAL FUTURO) ==================================
        case 'DELETE':
            // Exemplo: ?tabela=linguagens&id=5
            $tabela = $_GET['tabela'] ?? '';
            $id = (int)($_GET['id'] ?? 0);

            $tabelas_permitidas = ['linguagens', 'categorias', 'perguntas'];
            if (!in_array($tabela, $tabelas_permitidas) || $id <= 0) {
                sendResponse(400, ['message' => 'Parâmetros inválidos']);
            }

            $id = mysqli_real_escape_string($conn, $id);
            $query = "DELETE FROM $tabela WHERE ID = $id";

            if (mysqli_query($conn, $query)) {
                sendResponse(200, ['message' => 'Registro excluído com sucesso']);
            } else {
                sendResponse(500, ['message' => 'Erro ao excluir']);
            }
            break;


        default:
            sendResponse(405, ['message' => 'Método HTTP não suportado']);
            break;
    }

    mysqli_close($conn);
?>