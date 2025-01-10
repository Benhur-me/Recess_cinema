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

                    // Redirect to a protected page (e.g., dashboard or homepage)
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
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f9;
        }

        form {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .error-message {
            color: #d32f2f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 0.9em;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            background: #f9f9f9;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
            background: #ffffff;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 15px;
            color: #666;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <h1>Login</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit" name="login">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
