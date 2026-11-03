 
<?php

require_once "../config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {

        $message = "All fields are required!";
        $message_type = "error";

    } else {

        $check_sql = "SELECT id FROM users WHERE email = ?";

        $check_stmt = $conn->prepare($check_sql);

        $check_stmt->bind_param("s", $email);

        $check_stmt->execute();

        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {

            $message = "This email is already registered!";
            $message_type = "error";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {

    $message = "Registration successful! Redirecting to login...";
    $message_type = "success";

    header("refresh:2;url=/student_management_system/auth/login.php");

}else {

                $message = "Registration failed!";
                $message_type = "error";
            }
        }
    }
}
?> 

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>User Registration</h2>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name">

       

        <label>Email:</label>
        <input type="email" name="email">

        

        <label>Password:</label>
        <input type="password" name="password">

       

        <button type="submit">Register</button>

    </form>
    <?php if (!empty($message)): ?>

    <p class="message <?php echo $message_type; ?>">
        <?php echo $message; ?>
    </p>

<?php endif; ?>
</div>
</body>
</html>