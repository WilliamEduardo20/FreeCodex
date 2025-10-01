<?php
    session_start();

    session_unset();

    session_destroy();
    header('Location: /caminho/index.html');
    exit;
?>