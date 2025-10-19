<?php
    require_once('../Utils/app/httpHelper.php');
    require_once('../Utils/app/tokenHelper.php');
    require_once('../Config/conection.php');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    session_start();

    $change = request('change');

    switch ($change) {
        case 'updateNome':
            $id = $_SESSION['id'];
            $nomeNovo = request('novoNome');

            $query = "UPDATE usuarios SET Nome = ? WHERE ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'si', $nomeNovo, $id);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['nome'] = $nomeNovo;
            } else {
                sendResponse(500, ['message' => 'Errro ao atualizar: ' . mysqli_error($conn)]);
            }

            header('Location: /FreeCodex/Src/Views/html/user.php?sucesso');
            exit;
            break;

        case 'updateEmail':
            $id = $_SESSION['id'];
            $senhaDigitada = request('senhaAtual');
            $emailNovo = request('novoEmail');

            $query = "SELECT Senha, Status FROM usuarios WHERE ID = ?";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);
            if ($row['Status'] === 'a' && password_verify($senhaDigitada, $row['Senha'])) {
                $query = "UPDATE usuarios SET Email = ? WHERE ID = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'si', $emailNovo, $id);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['email'] = $emailNovo;
                } else {
                    sendResponse(500, ['message' => 'Errro ao atualizar: ' . mysqli_error($conn)]);
                }

                header('Location: /FreeCodex/Src/Views/html/user.php?sucesso');
                exit;
            } else {
                header('Location: /FreeCodex/Src/Views/html/user.php?change=senha-incorreta');
                exit;
            }
            break;

        case 'updateTelefone':
            $id = $_SESSION['id'];
            $senhaDigitada = request('senhaAtual');
            $telefoneNovo = request('novoTelefone');

            $query = "SELECT Senha, Status FROM usuarios WHERE ID = ?";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);
            if ($row['Status'] === 'a' && password_verify($senhaDigitada, $row['Senha'])) {
                $query = "UPDATE usuarios SET Telefone = ? WHERE ID = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'si', $telefoneNovo, $id);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['telefone'] = $telefoneNovo;
                } else {
                    sendResponse(500, ['message' => 'Errro ao atualizar: ' . mysqli_error($conn)]);
                }

                header('Location: /FreeCodex/Src/Views/html/user.php?sucesso');
                exit;
            } else {
                header('Location: /FreeCodex/Src/Views/html/user.php?change=senha-incorreta');
                exit;
            }
            break;

        case 'updateSenha':
            $id = $_SESSION['id'];
            $senhaDigitada = request('senhaAtual');
            $senhaNova = request('novaSenha');

            $query = "SELECT Senha, Status FROM usuarios WHERE ID = ?";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);
            if ($row['Status'] === 'a' && password_verify($senhaDigitada, $row['Senha'])) {
                $query = "UPDATE usuarios SET Senha = ? WHERE ID = ?";
                $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'si', $senhaNova, $id);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['senha'] = $senhaNova;
                } else {
                    sendResponse(500, ['message' => 'Errro ao atualizar: ' . mysqli_error($conn)]);
                }

                header('Location: /FreeCodex/Src/Views/html/user.php?sucesso');
                exit;
            } else {
                header('Location: /FreeCodex/Src/Views/html/user.php?change=senha-incorreta');
                exit;
            }
            break;

        default:
            sendResponse(405, ['massage' => 'Tipo de mudança não suportada']);
            break;
    }
?>