<?php
    session_start();

    session_unset();

    session_destroy();
    header('Location: /FreeCodex2/index.html');
    exit;
?>