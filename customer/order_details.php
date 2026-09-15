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
    header("Location: orders.php");
    exit;
}

$order_id = (int) $_GET["id"];


// Get order
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


// Order not found
if (!$order) {
    header("Location: orders.php");
    exit;
}


// Get order items
$sql = "SELECT
            order_items.quantity,
            order_items.price,
            order_items.subtotal,
            medicines.name
        FROM order_items
        INNER JOIN medicines
        ON order_items.medicine_id = medicines.id
        WHERE order_items.order_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $order_id
]);

$items = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<link rel="stylesheet" href="../css/style.css">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Details - E-Pharmacy</title>

    <style>

    body{
    margin:0;
    font-family:Arial,sans-serif;
    background-image:url("../images/dashboard_bg.png");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment:fixed;
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
            width: 85%;
            margin: 40px auto;
        }

        h1 {
            color: #1976d2;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.1);
        }

        .order-info {
            margin-bottom: 30px;
        }

        .order-info p {
            font-size: 17px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f5f9;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin-top: 25px;
        }

        .button {
            display: inline-block;

            margin-top: 25px;

            padding: 10px 20px;

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

        <a href="orders.php">
            My Orders
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>

    </div>

</div>


<div class="container">

    <h1>
        Order #<?php echo $order["id"]; ?>
    </h1>


    <div class="box">

        <div class="order-info">

            <p>
                <strong>Order ID:</strong>
                #<?php echo $order["id"]; ?>
            </p>

            <p>
                <strong>Date:</strong>

                <?php

                echo date(
                    "d M Y, h:i A",
                    strtotime($order["created_at"])
                );

                ?>

            </p>

        </div>


        <h2>Order Items</h2>


        <table>

            <tr>

                <th>
                    Medicine
                </th>

                <th>
                    Price
                </th>

                <th>
                    Quantity
                </th>

                <th>
                    Subtotal
                </th>

            </tr>


            <?php foreach ($items as $item): ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $item["name"]
                        );
                        ?>
                    </td>

                    <td>
                        Rs.
                        <?php
                        echo number_format(
                            $item["price"],
                            2
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $item["quantity"];
                        ?>
                    </td>

                    <td>
                        Rs.
                        <?php
                        echo number_format(
                            $item["subtotal"],
                            2
                        );
                        ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>


        <div class="total">

            Total:

            Rs.
            <?php
            echo number_format(
                $order["total_amount"],
                2
            );
            ?>

        </div>


        <a
            href="orders.php"
            class="button"
        >
            ← Back to My Orders
        </a>

    </div>

</div>

</body>

</html>