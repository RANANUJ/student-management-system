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

/* Message variables */
$message = "";
$message_type = "";


/* Check whether the user already has a profile */

$check_sql = "SELECT id FROM students WHERE user_id = ?";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param("i", $user_id);

$check_stmt->execute();

$check_result = $check_stmt->get_result();


/* If profile already exists */

if ($check_result->num_rows > 0) {

    $message = "You already have a student profile.";
    $message_type = "error";

}


/* Save profile only when form is submitted */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* Only continue if the user does not already have a profile */

    if ($check_result->num_rows === 0) {


        /* Get form data */

        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $course = trim($_POST["course"]);
        $age = (int) $_POST["age"];


        /* =========================
           SERVER-SIDE VALIDATION
        ========================= */

        /* Validate Name */

        if (empty($name)) {

            $message = "Name is required.";
            $message_type = "error";


        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {

            $message = "Name can contain only letters and spaces.";
            $message_type = "error";


        /* Validate Email */

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $message = "Please enter a valid email address.";
            $message_type = "error";


        /* Validate Course */

        } elseif (empty($course)) {

            $message = "Course is required.";
            $message_type = "error";


        /* Validate Age */

        } elseif ($age < 16 || $age > 100) {

            $message = "Age must be between 16 and 100.";
            $message_type = "error";


        } else {

            /* =========================
               DATA IS VALID
               NOW INSERT INTO DATABASE
            ========================= */

            $sql = "INSERT INTO students (user_id, name, email, course, age)
                    VALUES (?, ?, ?, ?, ?)";


            /* Prepare query */

            $stmt = $conn->prepare($sql);


            /* Put variables into SQL query */

            $stmt->bind_param(
                "isssi",
                $user_id,
                $name,
                $email,
                $course,
                $age
            );


            /* Execute query */

            if ($stmt->execute()) {

                $message = "Profile saved successfully!";
                $message_type = "success";

            } else {

                $message = "Database error: " . $stmt->error;
                $message_type = "error";
            }

        }

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add My Student Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/student-profile.css">

</head>

<body>

    <div class="student-page">

        <div class="student-card">

            <h2>Add My Student Profile</h2>

            <p class="subtitle">
                Enter your student information below.
            </p>


            <!-- Show success or error message here -->

            <?php if (!empty($message)): ?>

                <p class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <div class="form-row">

                    <label>Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <div class="form-row">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email address"
                        required
                    >

                </div>


                <div class="form-row">

                    <label>Course</label>

                    <input
                        type="text"
                        name="course"
                        placeholder="Example: BCA"
                        required
                    >

                </div>


                <div class="form-row">

                    <label>Age</label>

                    <input
                        type="number"
                        name="age"
                        placeholder="Enter your age"
                        min="1"
                        required
                    >

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        Save Profile
                    </button>

                    <a
                        href="/student_management_system/user/dashboard.php"
                        class="back-btn"
                    >
                        Back to Dashboard
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>