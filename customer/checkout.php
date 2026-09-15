<?php

require_once "../config/role_check.php";
requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT cart.medicine_id, cart.quantity,
               medicines.name, medicines.price
        FROM cart
        JOIN medicines ON cart.medicine_id = medicines.id
        WHERE cart.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    header("Location: cart.php");
    exit;
}

$total = 0;

foreach ($cart_items as $item) {
    $total += $item["price"] * $item["quantity"];
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Checkout - E-Pharmacy</title>

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

        header {
            background: #1976d2;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
        }

        header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            width: 85%;
            max-width: 900px;
            margin: 40px auto;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #1976d2;
            margin-top: 20px;
        }

        .buttons {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            cursor: pointer;
        }

        .back {
            background: #777;
        }

        .place {
            background: #1976d2;
        }
    </style>

</head>

<body>

<header>

    <strong>💊 E-Pharmacy</strong>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="medicines.php">Medicines</a>
        <a href="cart.php">Cart</a>
        <a href="../auth/logout.php">Logout</a>
    </nav>

</header>

<div class="container">

    <h1>Checkout</h1>

    <div class="box">

        <h2>Order Summary</h2>

        <table>

            <tr>
                <th>Medicine</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach ($cart_items as $item): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($item["name"]) ?>
                    </td>

                    <td>
                        Rs. <?= number_format($item["price"], 2) ?>
                    </td>

                    <td>
                        <?= $item["quantity"] ?>
                    </td>

                    <td>
                        Rs.
                        <?= number_format(
                            $item["price"] * $item["quantity"],
                            2
                        ) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

        <div class="total">
            Total: Rs. <?= number_format($total, 2) ?>
        </div>

        <div class="buttons">

            <a href="cart.php" class="btn back">
                ← Back to Cart
            </a>

            <form action="place_order.php" method="POST">

                <button class="btn place" type="submit">
                    ✓ Place Order
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>