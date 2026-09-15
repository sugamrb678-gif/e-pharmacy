<?php
require_once "../config/role_check.php";
requireRole(["pharmacist"]);
require_once "../config/database.php";

$sql="SELECT p.*,u.name customer_name,u.email customer_email,
m.name medicine_name,pi.quantity,pi.dosage
FROM prescriptions p
LEFT JOIN users u ON p.user_id=u.id
LEFT JOIN prescription_items pi ON p.id=pi.prescription_id
LEFT JOIN medicines m ON pi.medicine_id=m.id
ORDER BY p.id DESC";

$stmt=$conn->prepare($sql);
$stmt->execute();
$prescriptions=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Prescriptions - E-Pharmacy</title>

<style>
*{box-sizing:border-box}
body{
 margin:0;font-family:Arial;
 background:#eef8ff url("../images/dashboard_bg.png") center/cover fixed;
 color:#12345b;
}

/* NAVBAR */
header{
 height:70px;padding:0 40px;
 background:linear-gradient(135deg,#1976d2,#0d47a1);
 display:flex;align-items:center;
 box-shadow:0 3px 15px #777;
}
.logo{
 color:white;font-size:25px;font-weight:bold;
 margin-right:auto;
}
nav{display:flex;gap:8px}
nav a{
 color:white;text-decoration:none;
 padding:11px 15px;border-radius:10px;
 transition:.3s;
}
nav a:hover{
 background:rgba(255,255,255,.2);
 transform:translateY(-4px);
}

/* PAGE */
.container{
 max-width:1200px;
 margin:35px auto;
 padding:0 20px;
}
h1{color:#1976d2}

.grid{
 display:grid;
 grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
 gap:22px;
}

.card{
 background:rgba(255,255,255,.96);
 padding:22px;
 border-radius:16px;
 box-shadow:0 5px 18px #cbd5df;
 transition:.3s;
}
.card:hover{
 transform:translateY(-5px);
 box-shadow:0 8px 25px #aebdcc;
}

.top{
 display:flex;
 justify-content:space-between;
 align-items:center;
}
.icon{font-size:32px}

.status{
 padding:7px 13px;
 border-radius:20px;
 font-weight:bold;
}
.pending{background:#fff3cd;color:#b77900}
.approved{background:#e8f5e9;color:#2e7d32}
.rejected{background:#ffebee;color:#c62828}

h2{color:#1976d2}

.info{line-height:1.8}

.medicine{
 background:#e3f2fd;
 padding:12px;
 border-radius:9px;
 margin:15px 0;
}

.btn{
 display:block;
 width:100%;
 padding:11px;
 margin-top:10px;
 border:0;
 border-radius:7px;
 color:white;
 text-align:center;
 text-decoration:none;
 cursor:pointer;
 font-weight:bold;
}
.view{background:#1976d2}
.approve{background:#2e7d32}
.reject,.delete{background:#d32f2f}

.actions{
 display:flex;
 gap:10px;
}
.actions form{flex:1}
</style>
</head>

<body>

<header>
 <div class="logo">💊 E-Pharmacy</div>

 <nav>
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="inventory.php">💊 Inventory</a>
  <a href="prescriptions.php">📋 Prescriptions</a>
  <a href="orders.php">🛒 Orders</a>
  <a href="../auth/logout.php">🚪 Logout</a>
 </nav>
</header>

<div class="container">

<h1>📋 Customer Prescriptions</h1>
<p>Review and approve prescriptions for individual medicines.</p>

<div class="grid">

<?php foreach($prescriptions as $p):
$status=$p["status"]??"pending";
$file=!empty($p["prescription_file"])
 ? "../uploads/prescriptions/".rawurlencode(basename($p["prescription_file"])):"";
?>

<div class="card">

<div class="top">
 <div class="icon">📄</div>
 <span class="status <?=$status?>">
  <?=ucfirst(htmlspecialchars($status))?>
 </span>
</div>

<h2>Prescription #<?=$p["id"]?></h2>

<div class="info">
<b>Customer:</b> <?=htmlspecialchars($p["customer_name"]??"Unknown")?><br>
<b>Email:</b> <?=htmlspecialchars($p["customer_email"]??"N/A")?>
</div>

<div class="medicine">
 <b>💊 Medicine:</b>
 <?=htmlspecialchars($p["medicine_name"]??"Not linked")?>

 <?php if($p["quantity"]): ?>
 <br><b>Quantity:</b> <?=$p["quantity"]?>
 <?php endif; ?>

 <?php if($p["dosage"]): ?>
 <br><b>Dosage:</b> <?=htmlspecialchars($p["dosage"])?>
 <?php endif; ?>
</div>

<b>📅 Uploaded:</b>
<?=htmlspecialchars($p["uploaded_at"]??"N/A")?>

<?php if($file): ?>
<a class="btn view" href="<?=$file?>" target="_blank">
 👁 View Prescription
</a>
<?php endif; ?>

<?php if($status=="pending"): ?>

<div class="actions">

<form action="approve_prescription.php" method="POST">
 <input type="hidden" name="prescription_id" value="<?=$p["id"]?>">
 <button class="btn approve">✓ Approve</button>
</form>

<form action="reject_prescription.php" method="POST">
 <input type="hidden" name="prescription_id" value="<?=$p["id"]?>">
 <button class="btn reject">✕ Reject</button>
</form>

</div>

<?php endif; ?>

<form action="delete_prescription.php" method="POST"
 onsubmit="return confirm('Delete this prescription?')">
 <input type="hidden" name="id" value="<?=$p["id"]?>">
 <button class="btn delete">🗑 Delete</button>
</form>

</div>

<?php endforeach; ?>

</div>
</div>

</body>
</html>