
<?php
require_once "../config/role_check.php";
requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id, total_amount, status, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>

<title>My Orders</title>

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
 color:white;text-decoration:none;padding:12px 15px;
 border-radius:10px;font-weight:bold;transition:.3s;
 position:relative

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
    max-width: 900px;
    margin: 40px auto;
}

.card {
    background: white;
    padding: 20px;
    margin: 15px 0;
    border-radius: 10px;
    box-shadow: 0 3px 10px #ddd;
}

.status {
    color: #1976d2;
    font-weight: bold;
}

.btn {
    display: inline-block;
    background: #1976d2;
    color: white;
    padding: 9px 15px;
    border-radius: 5px;
    text-decoration: none;
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

<h1>📦 My Orders</h1>

<?php if (empty($orders)): ?>

<div class="card">
    <h2>No Orders Yet</h2>
    <p>You have not placed any orders.</p>

    <a href="medicines.php" class="btn">
        Browse Medicines
    </a>
</div>

<?php else: ?>

<?php foreach ($orders as $order): ?>

<div class="card">

    <h2>
        Order #<?= $order["id"] ?>
    </h2>

    <p>
        <strong>Total:</strong>
        Rs. <?= number_format($order["total_amount"], 2) ?>
    </p>

    <p>
        <strong>Status:</strong>
        <span class="status">
            <?= htmlspecialchars($order["status"]) ?>
        </span>
    </p>

    <p>
        <strong>Date:</strong>
        <?= htmlspecialchars($order["created_at"]) ?>
    </p>

    <a
        href="order_details.php?id=<?= $order["id"] ?>"
        class="btn"
    >
        View Order
    </a>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>

