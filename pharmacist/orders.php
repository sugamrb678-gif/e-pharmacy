<?php
$c=new mysqli("localhost","root","","e_pharmacy");

if(isset($_GET['approve'])){
    $id=(int)$_GET['approve'];
    $c->query("UPDATE orders SET status='Approved' WHERE id=$id");
    header("Location: orders.php"); exit;
}

if(isset($_GET['reject'])){
    $id=(int)$_GET['reject'];
    $c->query("UPDATE orders SET status='Rejected' WHERE id=$id");
    header("Location: orders.php"); exit;
}

$d=$c->query("SELECT o.*,i.medicine_id,i.quantity,i.price
              FROM orders o LEFT JOIN order_items i ON o.id=i.order_id
              ORDER BY o.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Orders - E-Pharmacy</title>

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

/* ORDERS */
.box{
    width:92%;max-width:1200px;
    margin:40px auto;padding:25px;
    background:rgba(255,255,255,.95);
    border-radius:18px;
    box-shadow:0 5px 20px #bbb;
}
h1{margin:0;color:#1976d2}
p{color:#666}

table{
    width:100%;border-collapse:collapse;
    margin-top:25px;
    overflow:hidden;
}
th{
    background:#1976d2;color:white;
    padding:14px;text-align:left;
}
td{
    padding:13px;
    border-bottom:1px solid #ddd;
}
tr:hover{background:#f1f8ff}

.approve,.reject{
    color:white;padding:8px 12px;
    border-radius:7px;text-decoration:none;
    display:inline-block;margin:2px;
}
.approve{background:#28a745}
.reject{background:#e53935}
.approve:hover,.reject:hover{opacity:.8}

.status{font-weight:bold}
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
    </nav>
</header>

<div class="box">

<h1>🛒 Customer Orders</h1>
<p>Review and manage customer orders.</p>

<table>
<tr>
    <th>Order</th>
    <th>Customer</th>
    <th>Medicine</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($r=$d->fetch_assoc()){ ?>

<tr>
    <td>#<?=$r['id']?></td>
    <td>Customer #<?=$r['user_id']?></td>
    <td>Medicine #<?=$r['medicine_id']?></td>
    <td><?=$r['quantity']?></td>
    <td>Rs. <?=$r['total_amount']?></td>

    <td class="status">
        <?=$r['status']=="Approved"?"🟢 Approved":
          ($r['status']=="Rejected"?"🔴 Rejected":"🟡 Pending")?>
    </td>

    <td>
    <?php if($r['status']=="Pending"){ ?>

        <a class="approve"
           href="?approve=<?=$r['id']?>"
           onclick="return confirm('Approve this order?')">
           ✓ Approve
        </a>

        <a class="reject"
           href="?reject=<?=$r['id']?>"
           onclick="return confirm('Reject this order?')">
           ✕ Reject
        </a>

    <?php }else{ ?>
        —
    <?php } ?>
    </td>
</tr>

<?php } ?>

</table>
</div>

</body>
</html>