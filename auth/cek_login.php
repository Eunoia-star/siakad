<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| CEK APAKAH SUDAH LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['login'])) {

    header("Location: /siakad/login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| FUNCTION CEK ROLE
|--------------------------------------------------------------------------
*/

function checkRole($role)
{
    if ($_SESSION['role'] !== $role) {

        header("Location: /siakad/login.php");
        exit;
    }
}