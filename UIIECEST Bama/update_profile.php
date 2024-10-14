<?php
// Start the session to check if the user is logged in
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: portal.html');
    exit;
}

// Include database connection file
include('db_connection.php');

// Define variables and initialize with empty values
$name = $email = $phone = $additional_info = "";
$profile_photo = "";
$error_msg = $success_msg = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize name
    if (!empty($_POST["name"])) {
        $name = htmlspecialchars(trim($_POST["name"]));
    } else {
        $error_msg = "Please enter your name.";
    }

    // Validate and sanitize email
    if (!empty($_POST["email"])) {
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email = htmlspecialchars(trim($_POST["email"]));
        } else {
            $error_msg = "Invalid email format.";
        }
    } else {
        $error_msg = "Please enter your email.";
    }

    // Validate and sanitize phone number
    if (!empty($_POST["phone"])) {
        $phone = htmlspecialchars(trim($_POST["phone"]));
    } else {
        $error_msg = "Please enter your phone number.";
    }

    // Sanitize additional info
    if (!empty($_POST["additional-info"])) {
        $additional_info = htmlspecialchars(trim($_POST["additional-info"]));
    }

    // Check if the profile photo was uploaded
    if (isset($_FILES["profile-photo"]) && $_FILES["profile-photo"]["error"] == 0) {
        // File properties
        $file_tmp = $_FILES["profile-photo"]["tmp_name"];
        $file_name = basename($_FILES["profile-photo"]["name"]);
        $file_size = $_FILES["profile-photo"]["size"];
        $file_type = $_FILES["profile-photo"]["type"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        // Check if file extension is valid
        if (in_array($file_ext, $allowed_extensions)) {
            // Check file size (limit: 2MB)
            if ($file_size <= 2097152) {
                // Generate a unique file name and move the uploaded file
                $new_file_name = uniqid() . "_" . $file_name;
                $upload_dir = "uploads/profile_pics/" . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_dir)) {
                    $profile_photo = $new_file_name;
                } else {
                    $error_msg = "Error uploading your profile photo.";
                }
            } else {
                $error_msg = "File size must be less than 2MB.";
            }
        } else {
            $error_msg = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    // If no errors, update the profile in the database
    if (empty($error_msg)) {
        // Prepare an update query
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, additional_info = ?, profile_photo = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_email, $param_phone, $param_additional_info, $param_profile_photo, $param_id);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_phone = $phone;
            $param_additional_info = $additional_info;
            $param_profile_photo = $profile_photo;
            $param_id = $_SESSION['id'];  // Assuming user ID is stored in session

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Success message
                $success_msg = "Profile updated successfully!";
                // Optionally, update session variables with new data
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                $_SESSION['profile_photo'] = $profile_photo;
            } else {
                $error_msg = "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS -->
    <style>
        .message {
            text-align: center;
            margin: 20px 0;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Update Profile</h2>

        <?php if (!empty($error_msg)) : ?>
            <div class="message error"><?= $error_msg ?></div>
        <?php elseif (!empty($success_msg)) : ?>
            <div class="message success"><?= $success_msg ?></div>
        <?php endif; ?>

        <a href="profile.html">Go back to Profile</a>
    </div>

</body>
</html>
