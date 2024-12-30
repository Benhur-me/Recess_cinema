<?php
// Start the session
session_start();

// Include database connection
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'admin'; // Default role for registration

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if the email already exists
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            // Hash the password before saving it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into the database
            $sql = "INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $name, $email, $hashed_password, $role);
            $stmt->execute();

            $_SESSION['admin_email'] = $email;
            header("Location: admin_login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Include previous styles here... */
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

        .register-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .register-container .icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-container .icon i {
            font-size: 50px;
            color: #007BFF;
        }

        .register-container .form-group {
            margin-bottom: 20px;
        }

        .register-container .form-group label {
            font-size: 14px;
            font-weight: bold;
        }

        .register-container .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .register-container .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .register-container .btn {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .register-container .btn:hover {
            background-color: #0056b3;
        }

        .register-container .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .register-container .footer {
            text-align: center;
            margin-top: 20px;
        }

        .register-container .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .register-container .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="icon">
            <i class="fas fa-user-circle"></i> <!-- User Icon -->
        </div>
        <h2>Admin Register</h2>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        <form action="admin_register.php" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="footer">
            <p>Already have an account? <a href="admin_login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
