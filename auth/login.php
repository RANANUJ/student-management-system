<?php
session_start();
require_once "../config/database.php";
$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];
    $_SESSION["role"] = $user["role"];

   if ($user["role"] === "admin") {

   if ($user["role"] === "admin") {

    header("Location: /student_management_system/admin/dashboard.php");
    exit();

} elseif ($user["role"] === "user") {

    header("Location: /student_management_system/user/dashboard.php");
    exit();

}

} else {

    header("Location: ../user/dashboard.php");
    exit();
}

    } else {

            $message = "Incorrect password!";
            $message_type = "error";
        }

    } else {

        $message = "User not found!";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="container">

        <h2>Login</h2>

        <form method="POST">

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>

        </form>
        <?php if (!empty($message)): ?>

    <p class="message <?php echo $message_type; ?>">
        <?php echo $message; ?>
    </p>

<?php endif; ?>

        <div class="form-link">
            <p>Don't have an account?</p>
            <a href="register.php">Create an account</a>
        </div>

    </div>

</body>
</html>