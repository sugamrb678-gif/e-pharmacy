<?php

require_once "../config/role_check.php";

requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// Check order ID
if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {
    header("Location: dashboard.php");
    exit;
}

$order_id = (int) $_GET["id"];


// Get the customer's order
$sql = "SELECT id, total_amount, created_at
        FROM orders
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $order_id,
    $user_id
]);

$order = $stmt->fetch();


// Order doesn't exist
if (!$order) {
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Successful - E-Pharmacy</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background: #1976d2;
            color: white;
            padding: 20px 50px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
        }

        .container {
            width: 70%;
            margin: 80px auto;
            text-align: center;
        }

        .box {
            background: white;
            padding: 50px;
            border-radius: 10px;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.1);
        }

        .success-icon {
            font-size: 60px;
        }

        h1 {
            color: #1976d2;
        }

        .order-number {
            font-size: 20px;
            margin: 20px;
        }

        .button {
            display: inline-block;

            padding: 12px 25px;

            margin-top: 20px;

            background: #1976d2;

            color: white;

            text-decoration: none;

            border-radius: 5px;
        }

        .button:hover {
            background: #1565c0;
        }

    </style>

</head>

<body>


<div class="navbar">

    <h2>💊 E-Pharmacy</h2>

    <div>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="medicines.php">
            Medicines
        </a>

        <a href="cart.php">
            Cart
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>

    </div>

</div>


<div class="container">

    <div class="box">

        <div class="success-icon">
            ✅
        </div>

        <h1>
            Order Placed Successfully!
        </h1>

        <div class="order-number">

            Order ID:

            <strong>
                #<?php echo $order["id"]; ?>
            </strong>

        </div>

        <p>

            Total Amount:

            <strong>
                Rs.
                <?php
                echo number_format(
                    $order["total_amount"],
                    2
                );
                ?>
            </strong>

        </p>

        <p>
            Thank you for your order.
        </p>


        <a
            href="dashboard.php"
            class="button"
        >
            Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>