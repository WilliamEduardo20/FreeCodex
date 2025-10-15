<?php
    require_once('../Utils/app/httpHelper.php');
    require_once('../Utils/app/tokenHelper.php');
    require_once('../Config/conection.php');
    header('Content-Type: application/json; charset=UTF-8');

    session_start();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            # code...
            break;

        case 'POST':
            $nome = request('nome');
            $email = request('email');
            $telefone = request('telefone');
            $nascimento = request('nascimento');
            $senha = request('senha');

            if (empty($nome) || empty($email) || empty($telefone) || empty($nascimento) || empty($senha)) {
                sendResponse(400, ['message' => 'Campos obrigatórios não preenchidos']);
                exit;
            }

            $query = "SELECT Email FROM usuarios WHERE Email = ?";
            $stmt = mysqli_prepare($conn, $query);
            
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                sendResponse(400, ['message' => 'E-mail já cadastrado']);
                exit;
            }

            $token = tokenGenerate();
            $status = 'a';
            $senha = password_hash($senha, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuarios (Nome, Email, Telefone, DataNasc, Senha, Token, Status) VALUE (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssssss', $nome, $email, $telefone, $nascimento, $senha, $token, $status);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: /FreeCodex/Src/Views/html/login.html');
            } else {
                sendResponse(500, ['message' => 'Erro ao cadastrar: ' . mysqli_error($conn)]);
            }
            break;

        case 'PUT':
            $id = $_SESSION['id'];
            $nome = request('nome');
            $email = request('email');
            $telefone = request('telefone');
            $nascimento = request('nascimento');
            $senha = request('senha');

            if (empty($id) || empty($nome) || empty($email) || empty($telefone) || empty($nascimento) || empty($senha)) {
                sendResponse(400, ['message' => 'Campos obrigatórios não preenchidos']);
                exit;
            }

            $query = "SELECT email FROM usuario WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);

            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                sendResponse(400, ['message' => 'E-mail já cadastrado!']);
                exit;
            }

            $senha = password_hash($senha, PASSWORD_DEFAULT);
            $query = "UPDATE usuario SET nome = ?, email = ?, telefone = ?, data_nasc = ?, senha = ? WHERE id = ?";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssssi', $nome, $email, $telefone, $nascimento, $senha, $id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['nascimento'] = $nascimento;
                $_SESSION['senha'] = $senha;
            } else {
                sendResponse(500, ['message' => 'Errro ao atualizar: ' . mysqli_error($conn)]);
            }
            break;

        case 'DELETE':
            $id = $_SESSION['id'];

            $query = "UPDATE usuario SET status = 'd' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: /caminho/login.html');
            } else {
                sendResponse(500, ['message' => 'Erro ao deletar conta: ' . mysqli_error($conn)]);
            }
            break;

        default:
            sendResponse(405, ['massage' => 'Método HTTP não suportado']);
            break;
    }
?>