<?php
    require_once __DIR__ . '/../Config/conection.php';

    function start() {
        global $conn;
        if (!isset($_SESSION['atual'])) {
            $_SESSION['atual'] = 1;
        }

        $query = "SELECT * FROM perguntas";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);
        $num_registros = mysqli_stmt_num_rows($stmt);
        $registros = floor($num_registros / 5);

        $_SESSION['total'] = $registros;
        $_SESSION['page'] = $_SESSION['atual'] . '/' . $_SESSION['total'];

        mysqli_stmt_close($stmt);
    }

    function nextPage() {
        if ($_SESSION['atual'] < $_SESSION['total']) {
            $_SESSION['atual']++;
            $_SESSION['page'] = $_SESSION['atual'] . '/' . $_SESSION['total'];
        }
    }

    function previousPage() {
        if ($_SESSION['atual'] > 1) {
            $_SESSION['atual']--;
            $_SESSION['page'] = $_SESSION['atual'] . '/' . $_SESSION['total'];
        }
    }

    function creatFAQ() {
        global $conn;
        $limite = 5;
        $offset = ($_SESSION['atual'] - 1) * $limite;

        $query = "SELECT Pergunta, Resposta FROM perguntas LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $limite, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<details>';
                echo '<summary>' . htmlspecialchars($row['Pergunta']) . '</summary>';
                echo '<p>' . htmlspecialchars($row['Resposta']) . '</p>';
                echo '</details>';
                echo '<hr>';
            }
        }
        mysqli_stmt_close($stmt);
    }
?>