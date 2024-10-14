<?php
// Start the session
session_start();

// Include the database connection file
include('db_connection.php');

// Define variables and initialize with empty values
$fullname = $email = $password = "";
$error_msg = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate the form inputs
    $fullname = htmlspecialchars(trim($_POST["fullname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST["password"]));

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    }

    // Password hashing for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the email parameter
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Store the result to check if email exists
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $error_msg = "This email is already registered.";
            } else {
                // Email doesn't exist, proceed with registration
                $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind the parameters
                    mysqli_stmt_bind_param($stmt, "sss", $param_fullname, $param_email, $param_password);

                    // Set parameters
                    $param_fullname = $fullname;
                    $param_email = $email;
                    $param_password = $hashed_password;

                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Registration successful, redirect to dashboard
                        $_SESSION['loggedin'] = true;
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['email'] = $email;
                        header('Location: dashboard.html');
                    } else {
                        $error_msg = "Something went wrong. Please try again later.";
                    }
                }
            }
        } else {
            $error_msg = "Something went wrong. Please try again.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .signup-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signup-container h2 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
        }

        .signup-container form {
            display: flex;
            flex-direction: column;
        }

        .signup-container input[type="text"],
        .signup-container input[type="email"],
        .signup-container input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signup-container button {
            padding: 10px;
            background-color: #1e1e1e;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-container button:hover {
            background-color: #ffcc00;
        }

        .signup-links {
            margin-top: 15px;
            text-align: center;
        }

        .signup-links a {
            color: #1e1e1e;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .signup-links a:hover {
            color: #ffcc00;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Sign Up</h2>

        <!-- Display error message if any -->
        <?php if (!empty($error_msg)) : ?>
            <div class="error-msg"><?= $error_msg; ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Account</button>
        </form>
        <div class="signup-links">
            <p><a href="portal.html">Already have an account? Sign In</a></p>
        </div>
    </div>

</body>
</html>
