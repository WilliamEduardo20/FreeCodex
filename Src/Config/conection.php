<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbName = "bancodex";

    $conn = new mysqli($host, $user, $pass, $dbName);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
?>