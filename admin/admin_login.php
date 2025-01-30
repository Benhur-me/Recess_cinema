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
        /* General styles */
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

        .container {
            display: flex;
            flex-direction: column;
            width: 90%;
            max-width: 800px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .container {
                flex-direction: row;
            }
        }

        .left-side {
            flex: 1;
            background-color: #007BFF;
            color: white;
            padding: 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-side h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .left-side p {
            font-size: 18px;
            line-height: 1.5;
        }

        .right-side {
            flex: 1;
            padding: 40px;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .login-container .form-group {
            margin-bottom: 20px;
        }

        .login-container .form-group label {
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .login-container .form-group input {
            width: 100%;
            padding: 12px 18px;
            margin: 10px 0;
            border-radius: 8px;
            border: 2px solid #ddd;
            background-color: #f9f9f9;
            font-size: 16px;
        }

        .login-container .form-group input:focus {
            border: 2px solid #007BFF;
            background-color: #ffffff;
            outline: none;
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
    <div class="container">
        <div class="left-side">
            <h1>Welcome, Admin!</h1>
            <p>Log in to access the admin dashboard, manage users, and oversee operations efficiently.</p>
        </div>
        <div class="right-side">
            <div class="login-container">
                <h2>Admin Login</h2>
                <?php if (!empty($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
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
                    <p><a href="#">Forgot password?</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
