<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include db.php for the database connection
include(__DIR__ . '/../db.php');

// Start the session (if you're using sessions for login tracking)
session_start();

// Check if the database connection is established
if (!isset($conn) || $conn === null) {
    die("Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL to select the admin user based on email
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param('s', $email); // Binding parameter
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // If password is correct, login is successful
            // Store user information in session
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the admin dashboard (dashboard.php)
            header('Location: dashboard.php');
            exit;  // Make sure to stop further execution
        } else {
            // If login fails
            $error = "Invalid email or password!";
        }

        $stmt->close();
    } else {
        $error = "Failed to prepare the SQL query!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Include the input field CSS here... */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .login-container .icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container .icon i {
            font-size: 50px;
            color: #007BFF;
        }

        .login-container .form-group {
            margin-bottom: 20px;
        }

        .login-container .form-group label {
            font-size: 14px;
            font-weight: bold;
        }

        .login-container .form-group input {
            width: 100%;
            padding: 12px 18px;
            margin: 10px 0;
            border-radius: 8px;
            border: 2px solid #ddd;
            background-color: #f9f9f9;
            font-size: 16px;
            color: #333;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .login-container .form-group input:focus {
            border: 2px solid #007BFF;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
            outline: none;
        }

        .login-container .form-group input::placeholder {
            color: #aaa;
            font-style: italic;
        }

        .login-container .btn {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container .btn:hover {
            background-color: #0056b3;
        }

        .login-container .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .login-container .footer {
            text-align: center;
            margin-top: 20px;
        }

        .login-container .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-container .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h2>Admin Login</h2>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        <form action="admin_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="admin_register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
