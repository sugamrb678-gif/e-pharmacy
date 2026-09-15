<?php
require_once "../config/role_check.php";
requireRole(["customer"]);
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id,prescription_file,status
    FROM prescriptions
    WHERE user_id=?
    ORDER BY id DESC
");
$stmt->execute([$user_id]);
$prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Prescriptions</title>
<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background-image:url("../images/dashboard_bg.png");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment:fixed;

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
.container{width:90%;max-width:700px;margin:40px auto}
.card{background:white;padding:20px;margin:15px 0;border-radius:10px;
box-shadow:0 3px 10px #ddd}
.btn{display:inline-block;background:#1976d2;color:white;padding:10px 18px;
border-radius:6px;text-decoration:none}
.status{color:#1976d2;font-weight:bold}
</style>
</head>

<body>

<header>

<a href="dashboard.php" class="logo">

</a>

<nav>
<a href="dashboard.php">🏠 Home</a>
<a href="medicines.php">💊 Medicines</a>
<a href="cart.php">🛒 Cart</a>
<a href="orders.php">📦 Orders</a>
<a href="../auth/logout.php">🚪 Logout</a>
</nav>

</header>

<div class="container">

<h1>📄 My Prescriptions</h1>

<a href="upload_prescription.php" class="btn">
+ Upload Prescription
</a>

<?php if(empty($prescriptions)): ?>

<div class="card">
<h2>No prescriptions found</h2>
</div>

<?php else: ?>

<?php foreach($prescriptions as $p): ?>

<div class="card">

<h2>Prescription #<?= $p["id"] ?></h2>

<p>
Status:
<?php if ($p["status"] === "approved"): ?>
    <span class="status" style="color:green;">✅ Approved</span>
<?php elseif ($p["status"] === "rejected"): ?>
    <span class="status" style="color:red;">❌ Rejected</span>
<?php else: ?>
    <span class="status" style="color:orange;">⏳ Pending</span>
<?php endif; ?>
</p>

<p>
File: <?= htmlspecialchars($p["prescription_file"]) ?>
</p>

<a class="btn" target="_blank"
href="../uploads/prescriptions/<?= rawurlencode($p["prescription_file"]) ?>">
👁 View Prescription
</a>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>