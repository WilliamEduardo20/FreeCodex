<?php
    require_once('./app/httpHelper.php');
    require_once('../Config/conection.php');
    session_start();

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $query = "SELECT Id, Nome, Email, Telefone, DataNasc, Token, Status FROM usuarios WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'] && $row['status'] === 'a')) {
           $_SESSION['id'] = $row['Id'];
           $_SESSION['nome'] = $row['Nome'];
           $_SESSION['email'] = $row['Email'];
           $_SESSION['telefone'] = $row['Telefone'];
           $_SESSION['nascimento'] = $row['DataNasc'];
           $_SESSION['token'] = $row['Token'];
           $_SESSION['status'] = $row['Status'];
           header('Location: /caminho/index.html');
           exit;
        } else {
            header('Location: /FreeCodex/Src/Views/html/login.html?login=erro');
            exit;
        }
    } else {
        header('Location: /FreeCodex/Src/Views/html/login.html?Erro=email-ou-senha-incorretos');
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>