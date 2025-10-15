<?php
    require_once __DIR__ . '/app/tokenHelper.php';
    require_once __DIR__ . '/../Config/conection.php';

    getUserByToken($conn);
?>