<?php
session_start();
require 'db_connect.php'; // Assuming this file contains your DB connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if user exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Fetch user details
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];

            // Redirect to dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            // Invalid password
            $error_message = 'Invalid password. Please try again.';
        }
    } else {
        // User not found
        $error_message = 'No user found with this email. Please sign up.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles4.css"> <!-- Link to your CSS file -->
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

        .portal-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .portal-container h2 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
        }

        .portal-container form {
            display: flex;
            flex-direction: column;
        }

        .portal-container input[type="email"],
        .portal-container input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .portal-container button {
            padding: 10px;
            background-color: #1e1e1e;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .portal-container button:hover {
            background-color: #ffcc00;
        }

        .portal-links {
            margin-top: 15px;
            text-align: center;
        }

        .portal-links a {
            color: #1e1e1e;
            text-decoration: none;
            font-size: 14px;
            margin: 0 5px;
            transition: color 0.3s ease;
        }

        .portal-links a:hover {
            color: #ffcc00;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="portal-container">
        <h2>Sign In</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="portal.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <div class="portal-links">
            <p><a href="signup.html">Don't have an account? Sign Up</a></p>
            <p><a href="forget.html">Forgot Password?</a></p>
        </div>
    </div>

</body>
</html>
