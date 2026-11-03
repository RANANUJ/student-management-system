<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /student_management_system/auth/login.php");
    exit();
}

if ($_SESSION["role"] !== "user") {
    header("Location: /student_management_system/dashboard.php");
    exit();
}

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM students WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$student = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>

    <title>My Student Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/student-profile.css">

</head>

<body>

    <div class="student-page">

        <div class="student-card">

            <h2>My Student Profile</h2>

            <p class="subtitle">
                Your personal student information.
            </p>

            <?php if ($student): ?>

                <div class="profile-details">

                    <div class="profile-item">
                        <strong>Name:</strong>
                        <span><?php echo htmlspecialchars($student["name"]); ?></span>
                    </div>

                    <div class="profile-item">
                        <strong>Email:</strong>
                        <span><?php echo htmlspecialchars($student["email"]); ?></span>
                    </div>

                    <div class="profile-item">
                        <strong>Course:</strong>
                        <span><?php echo htmlspecialchars($student["course"]); ?></span>
                    </div>

                    <div class="profile-item">
                        <strong>Age:</strong>
                        <span><?php echo htmlspecialchars($student["age"]); ?></span>
                    </div>

                </div>

            <?php else: ?>

                <p class="message error">
                    No student profile found.
                </p>

            <?php endif; ?>


           <div class="form-actions">

    <a
        href="/student_management_system/student/edit.php"
        class="save-btn"
    >
        Edit My Profile
    </a>
    <a
    href="/student_management_system/student/delete.php"
    class="delete-btn"
>
    Delete My Profile
</a>
    <a
        href="/student_management_system/user/dashboard.php"
        class="back-btn"
    >
        Back to Dashboard
    </a>

</div>
        </div>

    </div>

</body>
</html>