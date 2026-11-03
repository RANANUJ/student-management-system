<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /student_management_system/auth/login.php");
    exit();
}

if ($_SESSION["role"] === "admin") {

    header("Location: /student_management_system/admin/dashboard.php");
    exit();

} else {

    header("Location: /student_management_system/user/dashboard.php");
    exit();
}