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
            overflow: hidden;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transform: scale(0.8);
            animation: popIn 1s ease forwards;
        }

        .left-side {
            flex: 1;
            background-color: #007BFF;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            transform: translateX(-100%);
            animation: slideInLeft 1s ease forwards;
        }

        .left-side h1 {
            font-size: 32px;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 0.5s;
        }

        .left-side p {
            font-size: 18px;
            line-height: 1.5;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 0.8s;
        }

        .right-side {
            flex: 1;
            padding: 40px;
            transform: translateX(100%);
            animation: slideInRight 1s ease forwards;
        }

        /* Login form styles */
        .login-container {
            width: 100%;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 0.5s;
        }

        .login-container .icon {
            text-align: center;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 0.3s;
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
            transition: border 0.3s, box-shadow 0.3s, transform 0.2s;
        }

        .login-container .form-group input:focus {
            border: 2px solid #007BFF;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
            outline: none;
            transform: scale(1.05);
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
            transition: background-color 0.3s, transform 0.2s;
        }

        .login-container .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .login-container .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 0.7s;
        }

        .login-container .footer {
            text-align: center;
            margin-top: 20px;
            opacity: 0;
            animation: fadeIn 1.5s ease forwards 1s;
        }

        .login-container .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-container .footer a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0.8);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Side -->
        <div class="left-side">
            <h1>Welcome to Admin Portal</h1>
            <p>
                Manage your website effectively through the admin panel. 
                Here, you can oversee user activity, update content, 
                and ensure the smooth operation of the platform.
            </p>
        </div>

        <!-- Right Side -->
        <div class="right-side">
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
                <!-- <div class="footer">
                    <p>Don't have an account? <a href="admin_register.php">Register here</a></p>
                </div> -->
            </div>
        </div>
    </div>
</body>
</html>

