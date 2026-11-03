<?php

session_start();

/* Check if user is logged in */
if (!isset($_SESSION["user_id"])) {
    header("Location: /student_management_system/auth/login.php");
    exit();
}

/* Allow only normal users */
if ($_SESSION["role"] !== "user") {
    header("Location: /student_management_system/dashboard.php");
    exit();
}

/* Connect database */
require_once "../config/database.php";

/* Get logged-in user's ID */
$user_id = $_SESSION["user_id"];


/* Delete profile only after confirmation */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $delete_sql = "DELETE FROM students WHERE user_id = ?";

    $delete_stmt = $conn->prepare($delete_sql);

    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {

        header("Location: /student_management_system/user/dashboard.php");
        exit();

    } else {

        $message = "Unable to delete your profile.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Delete My Student Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/student-profile.css">

</head>

<body>

    <div class="student-page">

        <div class="student-card">

            <h2>Delete My Profile</h2>

            <p class="subtitle">
                Are you sure you want to delete your student profile?
            </p>

            <p class="message error">
                This action cannot be undone.
            </p>

            <form method="POST">

                <div class="form-actions">

                    <button
                        type="submit"
                        class="delete-btn"
                    >
                        Yes, Delete My Profile
                    </button>

                    <a
                        href="/student_management_system/student/view.php"
                        class="back-btn"
                    >
                        Cancel
                    </a>

                </div>

            </form>

            <?php if (!empty($message)): ?>

                <p class="message error">
                    <?php echo $message; ?>
                </p>

            <?php endif; ?>

        </div>

    </div>

</body>
</html>