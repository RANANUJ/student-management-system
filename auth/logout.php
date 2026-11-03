<?php

session_start();

$_SESSION = [];

session_destroy();

header("Location: /student_management_system/auth/login.php");
exit();