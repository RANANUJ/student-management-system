<?php

session_start();

/* Check if the user is logged in */
if (!isset($_SESSION["user_id"])) {
    header("Location: /student_management_system/auth/login.php");
    exit();
}

/* Allow only normal users */
if ($_SESSION["role"] !== "user") {
    header("Location: /student_management_system/dashboard.php");
    exit();
}

/* Connect to the database */
require_once "../config/database.php";

/* Get the logged-in user's ID */
$user_id = $_SESSION["user_id"];


/* Get the student's profile for the logged-in user only */

$sql = "SELECT * FROM students WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();


/* Get the student data */

$student = $result->fetch_assoc();


/* Check whether a profile exists */

$has_profile = $student !== null;

?>

<!DOCTYPE html>
<html>

<head>

    <title>User Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/user-dashboard.css">

</head>

<body>

    <div class="dashboard-container">

        <div class="dashboard-header">

            <div>

                <h2>User Dashboard</h2>

                <p>
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!
                </p>

            </div>

            <a
                href="/student_management_system/auth/logout.php"
                class="logout-btn"
            >
                Logout
            </a>

        </div>


        <div class="dashboard-content">

            <h3>Student Profile</h3>


            <?php if ($has_profile): ?>

                <div class="profile-summary">

                    <div class="summary-item">

                        <strong>Name:</strong>

                        <span>
                            <?php echo htmlspecialchars($student["name"]); ?>
                        </span>

                    </div>


                    <div class="summary-item">

                        <strong>Course:</strong>

                        <span>
                            <?php echo htmlspecialchars($student["course"]); ?>
                        </span>

                    </div>


                    <div class="summary-item">

                        <strong>Age:</strong>

                        <span>
                            <?php echo htmlspecialchars($student["age"]); ?>
                        </span>

                    </div>


                    <div class="summary-item">

                        <strong>Profile Created:</strong>

                        <span>
                           <?php echo date("F j, Y, g:i A", strtotime($student["created_at"])); ?>
                        </span>

                    </div>


                    <div class="summary-item">

                        <strong>Last Updated:</strong>

                        <span>
                           <?php echo date("F j, Y, g:i A", strtotime($student["updated_at"])); ?>
                        </span>

                    </div>

                </div>


                <a
                    href="/student_management_system/student/view.php"
                    class="profile-btn"
                >
                    View My Profile
                </a>


            <?php else: ?>

                <p>
                    You have not created your student profile yet.
                </p>

                <a
                    href="/student_management_system/student/create.php"
                    class="profile-btn"
                >
                    Add My Profile
                </a>

            <?php endif; ?>


        </div>

    </div>

</body>

</html>