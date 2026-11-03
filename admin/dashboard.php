<?php

session_start();

/* Check if user is logged in */

if (!isset($_SESSION["user_id"])) {

    header("Location: /student_management_system/auth/login.php");
    exit();
}


/* Allow only admins */

if ($_SESSION["role"] !== "admin") {

    header("Location: /student_management_system/user/dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container">

        <h2>Admin Dashboard</h2>

        <p>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!
        </p>

        <p>
            You are logged in as: Admin
        </p>

        <a href="/student_management_system/auth/logout.php">
            Logout
        </a>

    </div>

</body>

</html>