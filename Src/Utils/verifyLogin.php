<?php
    require_once('./app/httpHelper.php');
    require_once('../Config/conection.php');
    session_start();

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $query = "SELECT id, nome, email, telefone, data_nasc, token, status FROM usuario WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'] && $row['status'] === 'a')) {
           $_SESSION['id'] = $row['id'];
           $_SESSION['nome'] = $row['nome'];
           $_SESSION['email'] = $row['email'];
           $_SESSION['telefone'] = $row['telefone'];
           $_SESSION['nascimento'] = $row['data_nasc'];
           $_SESSION['token'] = $row['token'];
           $_SESSION['status'] = $row['status'];
           header('Location: /caminho/index.html');
           exit;
        } else {
            header('Location: /caminho/login.html?login=erro');
            exit;
        }
    } else {
        header('Location: /caminho/login.html?login=erro');
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>