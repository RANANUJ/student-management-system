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

/* Connect to database */
require_once "../config/database.php";

/* Get logged-in user's ID */
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $course = trim($_POST["course"]);
    $age = (int) $_POST["age"];

    $update_sql = "UPDATE students 
                   SET name = ?, email = ?, course = ?, age = ?
                   WHERE user_id = ?";

    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param(
        "sssii",
        $name,
        $email,
        $course,
        $age,
        $user_id
    );

    if ($update_stmt->execute()) {

        header("Location: /student_management_system/student/view.php");

        exit();

    } else {

        echo "Database error: " . $update_stmt->error;
    }
}

/* Get only this user's student profile */

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

    <title>Edit My Student Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/student-profile.css">

</head>

<body>

    <div class="student-page">

        <div class="student-card">

            <h2>Edit My Student Profile</h2>

            <p class="subtitle">
                Update your student information below.
            </p>

            <?php if ($student): ?>

                <form method="POST">

                    <div class="form-row">

                        <label>Name</label>

                        <input
                            type="text"
                            name="name"
                            value="<?php echo htmlspecialchars($student["name"]); ?>"
                            required
                        >

                    </div>


                    <div class="form-row">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($student["email"]); ?>"
                            required
                        >

                    </div>


                    <div class="form-row">

                        <label>Course</label>

                        <input
                            type="text"
                            name="course"
                            value="<?php echo htmlspecialchars($student["course"]); ?>"
                            required
                        >

                    </div>


                    <div class="form-row">

                        <label>Age</label>

                        <input
                            type="number"
                            name="age"
                            value="<?php echo htmlspecialchars($student["age"]); ?>"
                            min="1"
                            required
                        >

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            class="save-btn"
                        >
                            Update Profile
                        </button>

                        <a
                            href="/student_management_system/student/view.php"
                            class="back-btn"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            <?php else: ?>

                <p class="message error">
                    Student profile not found.
                </p>

            <?php endif; ?>

        </div>

    </div>

</body>
</html>