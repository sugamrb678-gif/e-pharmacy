<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT * FROM users
            WHERE email = ?
            AND role = 'pharmacist'
            AND status = 'active'
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user["password"])) {

        $message = "Invalid pharmacist email or password.";

    } else {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_role"] = "pharmacist";

        header("Location: ../pharmacist/dashboard.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>E-Pharmacy Pharmacist Login</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
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

        /* LOGIN BOX */

        .box {
            width: 515px;

            padding: 45px 38px;

            background: rgba(255,255,255,0.94);

            border-radius: 15px;

            box-shadow: 0 8px 30px rgba(0,0,0,0.25);

            backdrop-filter: blur(3px);
        }

        /* LOGO */

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;

            gap: 12px;

            margin-bottom: 25px;
        }

        .pill {
            width: 45px;
            height: 22px;

            background: linear-gradient(
                90deg,
                #ffb347 50%,
                #ff4081 50%
            );

            border-radius: 20px;

            transform: rotate(-45deg);

            position: relative;

            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }

        .pill span {
            position: absolute;

            width: 2px;
            height: 22px;

            background: rgba(255,255,255,0.8);

            left: 50%;
        }

        .logo-text {
            font-size: 32px;

            font-weight: bold;

            color: #1976d2;
        }

        /* HEADING */

        h3 {
            text-align: center;

            margin-bottom: 30px;

            font-size: 22px;
        }

        /* INPUT */

        input {
            width: 100%;

            padding: 15px;

            margin: 10px 0;

            border: 1px solid #aaa;

            border-radius: 5px;

            font-size: 16px;
        }

        input:focus {
            outline: none;

            border: 2px solid #1976d2;
        }

        /* LOGIN BUTTON */

        button {
            width: 100%;

            padding: 15px;

            margin-top: 15px;

            background: #1976d2;

            color: white;

            border: none;

            border-radius: 6px;

            font-size: 16px;

            cursor: pointer;
        }

        button:hover {
            background: #125ca1;
        }

        /* ERROR */

        .error {
            background: #ffebee;

            color: #c62828;

            padding: 10px;

            text-align: center;

            margin-bottom: 10px;

            border-radius: 5px;
        }

        /* LINKS */

        a {
            color: #1976d2;

            text-decoration: underline;
        }

        a:hover {
            color: #125ca1;
        }

    </style>

</head>

<body>

<div class="box">

    <!-- LOGO -->

    <div class="logo">

        <div class="pill">
            <span></span>
        </div>

        <span class="logo-text">
            E-Pharmacy
        </span>

    </div>


    <!-- HEADING -->

    <h3>
        Pharmacist Login
    </h3>


    <!-- ERROR MESSAGE -->

    <?php if ($message): ?>

        <div class="error">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <!-- LOGIN FORM -->

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Pharmacist Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            🔐 Pharmacist Login
        </button>

    </form>


    <!-- CUSTOMER LOGIN -->

    <p style="text-align:center;">

        Are you a customer?

        <a href="login.php">
            Customer Login
        </a>

    </p>


    <!-- HOME -->

    <p style="text-align:center;">

        <a href="../index.php">
            ← Back to Home
        </a>

    </p>

</div>

</body>

</html>