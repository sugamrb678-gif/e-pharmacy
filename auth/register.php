<?php

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check required fields
    if (empty($name) || empty($email) || empty($password)) {

        $message = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        // Check if email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "This email is already registered.";

        } else {

            // Hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert user
            $sql = "INSERT INTO users
                    (name, email, password, phone, role)
                    VALUES (?, ?, ?, ?, 'customer')";

            $stmt = $conn->prepare($sql);

            if ($stmt->execute([
                $name,
                $email,
                $hashed_password,
                $phone
            ])) {

                $message = "Registration-successful! You can now login.";

            } else {

                $message = "Registration-failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - E-Pharmacy</title>

    <style>

        body{
            margin: 0;
            font-family: Arial, sans-serif;

            background-image:
                linear-gradient(
                    rgba(255,255,255,0.35),
                    rgba(255,255,255,0.35)
                ),
                url("../images/login_bg.png");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: #1976d2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #125ca1;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #d32f2f;
        }

        .login {
            text-align: center;
            margin-top: 20px;
        }

        .login a {
            color: #1976d2;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Create Account</h2>

    <?php if (!empty($message)): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Name *</label>

        <input
            type="text"
            name="name"
            required
        >


        <label>Email *</label>

        <input
            type="email"
            name="email"
            required
        >


        <label>Phone</label>

        <input
            type="text"
            name="phone"
        >


        <label>Password *</label>

        <input
            type="password"
            name="password"
            required
        >


        <label>Confirm Password *</label>

        <input
            type="password"
            name="confirm_password"
            required
        >


        <button type="submit">
            Register
        </button>

    </form>


    <div class="login">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>

</div>

</body>

</html>