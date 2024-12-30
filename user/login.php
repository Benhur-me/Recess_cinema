<?php
// Include the database connection file
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        echo "Please enter both email and password!";
        exit;
    }

    // Prepare SQL query to check if the email exists
    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing the query: " . $conn->error;
        exit;
    }

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
            echo "Invalid login credentials!";
        }
    } else {
        echo "Invalid login credentials!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
