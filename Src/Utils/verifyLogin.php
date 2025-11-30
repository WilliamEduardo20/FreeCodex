<?php
    require_once('./app/httpHelper.php');
    require_once('../Config/conection.php');
    session_start();

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $query = "SELECT ID, Imagem, Nome, Email, Telefone, DataNasc, Senha, Token, Status FROM usuarios WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['Status'] === 'a' && password_verify($senha, $row['Senha'])) {
            $_SESSION['id'] = $row['ID'];
            $_SESSION['imagem'] = $row['Imagem'];
            $_SESSION['nome'] = $row['Nome'];
            $_SESSION['email'] = $row['Email'];
            $_SESSION['telefone'] = $row['Telefone'];
            $_SESSION['nascimento'] = $row['DataNasc'];
            $_SESSION['senha'] = $row['senha'];
            $_SESSION['token'] = $row['Token'];
            $_SESSION['status'] = $row['Status'];

            if ($_SESSION['email'] == 'regressor@gmail.com' || $_SESSION['email'] == 'srpacheco@gmail.com'|| $_SESSION['email'] == 'picanha@gmail.com') {
                header('Location: /FreeCodex/Src/Views/html/admin.php');
            } else {
                header('Location: /FreeCodex/');
            }
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