<?php
// Include the database connection file
include '../db.php';

$error_message = ''; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password!";
    } else {
        // Prepare SQL query to check if the email exists
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $error_message = "Error preparing the query: " . $conn->error;
        } else {
            // Bind email parameter and execute query
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if the user exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $db_email, $db_password);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $db_password)) {
                    // Start session and store user info
                    session_start();
                    $_SESSION['user_id'] = $id;
                    $_SESSION['email'] = $db_email;

                    // Redirect to user dashboard
                    header("Location: index.php");
                    exit;
                } else {
                    $error_message = "Invalid credentials!";
                }
            } else {
                $error_message = "Invalid credentials!";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, #f6d365, #fda085);
            animation: backgroundShift 10s infinite alternate;
        }

        @keyframes backgroundShift {
            0% {
                background: linear-gradient(120deg, #f6d365, #fda085);
            }
            100% {
                background: linear-gradient(120deg, #a1c4fd, #c2e9fb);
            }
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            background: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            overflow: hidden;
            transform: translateY(20px);
            animation: slideIn 1s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-section {
            flex: 1;
            background: #007BFF;
            color: white;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            animation: fadeIn 2s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .left-section h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: bold;
            animation: bounceIn 1s ease-in-out forwards;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.9);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .left-section p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #fff;
        }

        .left-section img {
            width: 80%;
            max-width: 250px;
            margin: 20px auto 0;
            border-radius: 8px;
        }

        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        form {
            width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #555;
        }

        .error-message {
            color: #d32f2f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            background: #f9f9f9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #ff7e5f;
            outline: none;
            box-shadow: 0 0 8px rgba(255, 126, 95, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        p {
            text-align: center;
            margin-top: 15px;
            color: #666;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
                margin: 20px;
            }

            .left-section {
                padding: 20px;
            }

            .left-section h2 {
                font-size: 2em;
            }

            .left-section p {
                font-size: 1em;
            }

            .form-section {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }

            input[type="email"], input[type="password"] {
                padding: 10px;
                font-size: 0.9em;
            }

            button {
                padding: 10px;
                font-size: 0.9em;
            }

            p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <h2>Welcome Back</h2>
            <p>
                Access your personalized user dashboard and stay connected with updates, features, and more!
            </p>
            <img src="https://via.placeholder.com/250" alt="User Access">
        </div>

        <!-- Right Section (Login Form) -->
        <div class="form-section">
            <form action="" method="POST">
                <h1>User Login</h1>
                <?php if (!empty($error_message)) : ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>

                <button type="submit">Login</button>
                <p>
                    Don't have an account? <a href="register.php">Sign up here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>