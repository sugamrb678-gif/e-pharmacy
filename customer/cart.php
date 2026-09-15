<?php

require_once "../config/role_check.php";
requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT 
            cart.id AS cart_id,
            cart.quantity,
            medicines.name,
            medicines.price,
            medicines.stock
        FROM cart
        INNER JOIN medicines
        ON cart.medicine_id = medicines.id
        WHERE cart.user_id = ?
        ORDER BY cart.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
$count = 0;

foreach ($cart_items as $item) {
    $total += $item["price"] * $item["quantity"];
    $count += $item["quantity"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Cart - E-Pharmacy</title>

<style>

     .logo{
    display:flex;
    align-items:center;
    gap:10px;
    color:white;
    text-decoration:none;
    font-size:24px;
    font-weight:bold;
}

.logo img{
    width:40px;
    height:40px;
    object-fit:contain;
}

* {
    box-sizing: border-box;
}

body{
    margin:0;
    font-family:Arial,sans-serif;
    background-image:url("../images/dashboard_bg.png");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment:fixed;
}

header{
 background:linear-gradient(135deg,#1976d2,#0d47a1);
 padding:15px 5%;display:flex;align-items:center;
 justify-content:space-between;box-shadow:0 3px 15px #555;
 position:sticky;top:0;z-index:10
}
.logo{color:white;font-size:25px;font-weight:bold}
.logo img{width:40px;vertical-align:middle}

nav{display:flex;gap:7px}
nav a{
 color:white;text-decoration:none;padding:12px 15px;
 border-radius:10px;font-weight:bold;transition:.3s;
 position:relative
}
nav a:hover{
 background:#ffffff25;transform:translateY(-4px)
}
nav a:after{
 content:"";position:absolute;bottom:5px;left:50%;
 width:0;height:3px;background:white;
 transition:.3s;transform:translateX(-50%)
}
nav a:hover:after{width:60%}

.container {
    width: 90%;
    max-width: 1000px;
    margin: 40px auto;
}

h2 {
    color: #1976d2;
}

.cart-item {
    background: white;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px #ddd;
}

.name {
    font-size: 18px;
    font-weight: bold;
    color: #1976d2;
}

.price {
    color: #555;
}

.quantity {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity button {
    border: none;
    background: #1976d2;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 5px;
    cursor: pointer;
}

.quantity button:hover {
    background: #125ca1;
}

.remove {
    color: #d32f2f;
    text-decoration: none;
    font-size: 13px;
}

.item-total {
    font-weight: bold;
    color: #1976d2;
}

.summary {
    background: white;
    padding: 25px;
    margin-top: 20px;
    border-radius: 10px;
    text-align: right;
    box-shadow: 0 2px 8px #ddd;
}

.total {
    font-size: 25px;
    font-weight: bold;
    color: #1976d2;
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 20px;
    border-radius: 6px;
    text-decoration: none;
    margin-left: 8px;
}

.continue {
    background: #777;
    color: white;
}

.checkout {
    background: #1976d2;
    color: white;
}

.empty {
    background: white;
    padding: 60px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 2px 8px #ddd;
}

.empty a {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 20px;
    background: #1976d2;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

</style>

</head>

<body>

<header>

   <a href="dashboard.php" class="logo">
    <img src="../images/logo_bg.png" alt="E-Pharmacy">
    <span>E-Pharmacy</span>
</a>

<nav>
<a href="dashboard.php"> 🏠Dashboard</a>
<a href="medicines.php">💊 Medicines</a>
<a href="cart.php">🛒 Cart</a>
<a href="orders.php">📦 Orders</a>
<a href="../auth/logout.php">🚪 Logout</a>
</nav>

</header>


<div class="container">

<h2>🛒 My Shopping Cart</h2>


<?php if ($cart_items): ?>


    <?php foreach ($cart_items as $item): ?>

    <div class="cart-item">

        <div>

            <div class="name">
                <?php echo htmlspecialchars($item["name"]); ?>
            </div>

            <p class="price">
                Rs. <?php echo number_format($item["price"], 2); ?>
                per item
            </p>

        </div>


        <div>

            <div class="quantity">

                <form action="update_cart.php" method="POST">

                    <input type="hidden"
                           name="cart_id"
                           value="<?php echo $item["cart_id"]; ?>">

                    <button type="submit"
                            name="quantity"
                            value="<?php echo max(1, $item["quantity"] - 1); ?>">
                        −
                    </button>

                </form>


                <strong>
                    <?php echo $item["quantity"]; ?>
                </strong>


                <form action="update_cart.php" method="POST">

                    <input type="hidden"
                           name="cart_id"
                           value="<?php echo $item["cart_id"]; ?>">

                    <button type="submit"
                            name="quantity"
                            value="<?php echo min($item["stock"], $item["quantity"] + 1); ?>">
                        +
                    </button>

                </form>

            </div>


            <a class="remove"
               href="remove_from_cart.php?id=<?php echo $item["cart_id"]; ?>"
               onclick="return confirm('Remove this medicine?');">
                Remove
            </a>

        </div>


        <div class="item-total">

            Rs.
            <?php
            echo number_format(
                $item["price"] * $item["quantity"],
                2
            );
            ?>

        </div>

    </div>

    <?php endforeach; ?>


    <div class="summary">

        <div class="total">

            Total:
            Rs. <?php echo number_format($total, 2); ?>

        </div>


        <a href="medicines.php"
           class="btn continue">
            ← Continue Shopping
        </a>


        <a href="checkout.php"
           class="btn checkout">
            Proceed to Checkout →
        </a>

    </div>


<?php else: ?>


    <div class="empty">

        <h2>Your cart is empty 🛒</h2>

        <p>
            Browse medicines and add something to your cart.
        </p>

        <a href="medicines.php">
            💊 Browse Medicines
        </a>

    </div>


<?php endif; ?>


</div>

</body>

</html>

