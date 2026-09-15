<?php

require_once "../config/role_check.php";
requireRole(["customer"]);
require_once "../config/database.php";

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) die("Invalid medicine ID.");

$stmt = $conn->prepare("
    SELECT * FROM medicines
    WHERE id=? AND status='available'
");
$stmt->execute([$id]);
$m = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$m) die("Medicine not found.");

/* Check approved prescription for THIS medicine */
$user_id = $_SESSION["user_id"] ?? 0;

$approvedPrescription = false;

if ((int)$m["prescription_required"] == 1) {

    $stmt = $conn->prepare("
        SELECT p.id
        FROM prescriptions p
        INNER JOIN prescription_items pi
            ON p.id = pi.prescription_id
        WHERE p.user_id = ?
        AND p.status = 'approved'
        AND pi.medicine_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $user_id,
        $id
    ]);

    $approvedPrescription = $stmt->fetch(PDO::FETCH_ASSOC);
}



?>
<!DOCTYPE html>
<html>
<head>
    

<title>Medicine Details - E-Pharmacy</title>
<link rel="stylesheet" href="../css/style.css">

<style>

    body{
    margin:0;
    font-family:Arial;
}


/* Header */


header{
    background:linear-gradient(135deg,#1976d2,#0d47a1);
    color:white;
    padding:18px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    color:white;
    text-decoration:none;
    font-size:24px;
    font-weight:bold;
}

nav{
    display:flex;
    gap:25px;
}

nav a{
    color:white;
    text-decoration:none;
}

/* Card */

.container{
    width:90%;
    max-width:650px;
    margin:60px auto;
}

.card{
    background:white;
    padding:35px;
    border-radius:15px;
    box-shadow:0 5px 20px #ccc;
}

h1{
    color:#1976d2;
    margin-top:0;
}

.info{
    margin:15px 0;
    font-size:16px;
}

.price{
    color:#1976d2;
    font-size:27px;
    font-weight:bold;
    margin:20px 0;
}

.warning{
    background:#fff3e0;
    color:#e65100;
    padding:15px;
    border-radius:8px;
    margin:20px 0;
}

.buttons{
    margin-top:30px;
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    border-radius:7px;
    text-decoration:none;
    color:white;
    background:#1976d2;
    font-weight:bold;
}

.buy{
    background:#43a047;
}

.pres{
    background:#f57c00;
}

.back{
    background:#777;
}

.disabled{
    background:#999;
    pointer-events:none;
}

</style>

</head>

<body>

<header>

<a href="dashboard.php" class="logo">
💊 E-Pharmacy
</a>

<nav>

<a href="dashboard.php">Dashboard</a>

<a href="medicines.php">Medicines</a>

<a href="cart.php">🛒 Cart</a>

<a href="../auth/logout.php">Logout</a>

</nav>

</header>


<div class="container">

<div class="card">

<h1>
💊 <?=htmlspecialchars($m["name"])?>
</h1>

<div class="info">
<b>Category:</b>
<?=htmlspecialchars($m["category"])?>
</div>

<div class="info">
<b>Description:</b><br>
<?=htmlspecialchars($m["description"])?>
</div>

<div class="price">
Rs. <?=number_format($m["price"],2)?>
</div>

<div class="info">
<b>Available Stock:</b>
<?=$m["stock"]?>
</div>

<div class="info">
<b>Expiry Date:</b>
<?=htmlspecialchars($m["expiry_date"])?>
</div>


<?php if ((int)$m["prescription_required"] == 1 && !$approvedPrescription): ?>

<div class="warning">
⚠ <b>Prescription Required</b>
<br><br>
You must upload a valid prescription before purchasing this medicine.
</div>

<div class="buttons">
<a href="upload_prescription.php?id=<?=$m["id"]?>" class="btn pres">
📄 Upload Prescription
</a>
</div>

<?php elseif ((int)$m["stock"] > 0): ?>

<div class="buttons">

<?php if ((int)$m["prescription_required"] == 1): ?>
<div class="info">✅ Approved prescription found.</div>
<?php endif; ?>

<a href="add_to_cart.php?id=<?=$m["id"]?>" class="btn">
🛒 Add to Cart
</a>

<a href="add_to_cart.php?id=<?=$m["id"]?>&buy=1" class="btn buy">
💳 Buy Now
</a>

</div>


<?php else: ?>

<div class="warning">

🔴 This medicine is currently out of stock.

</div>

<?php endif; ?>


<div class="buttons">

<a href="medicines.php" class="btn back">

← Back to Medicines

</a>

</div>

</div>

</div>

</body>
</html>