<?php
require_once "../config/role_check.php";
requireRole(["customer"]);
require_once "../config/database.php";

$q=$conn->query("
SELECT id,name,category,price,stock,expiry_date AS expiry,
prescription_required,image
FROM medicines
WHERE status='available' AND stock>0
ORDER BY name
");
$medicines=$q->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Medicines - E-Pharmacy</title>

<style>
*{box-sizing:border-box}
body{
 margin:0;font-family:Arial;color:#12345b;
 background:#eef8ff url("../images/dashboard_bg.png") center/cover fixed
}

/* NAVBAR */
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

.search input{
 padding:11px 15px;border:0;border-radius:20px;
 width:200px;outline:none
}

.container{width:90%;max-width:1200px;margin:35px auto}
.hero{text-align:center;margin-bottom:25px}
.hero small{color:#0879df;font-weight:bold}
.hero h1{font-size:38px;margin:8px}
.hero h1 span{color:#0879df}
.hero p{color:#667}

.features{
 display:grid;grid-template-columns:repeat(3,1fr);
 background:white;padding:18px;border-radius:15px;
 margin-bottom:25px;box-shadow:0 5px 20px #d5e5f2
}
.feature{text-align:center}
.feature b{display:block;margin:5px}
.feature small{color:#71849a}

.grid{
 display:grid;grid-template-columns:repeat(4,1fr);gap:18px
}

.card{
 background:white;border-radius:17px;overflow:hidden;
 box-shadow:0 5px 18px #ccd;transition:.3s
}
.card:hover{
 transform:translateY(-7px);
 box-shadow:0 10px 25px #aaa
}

.image-box{
 height:170px;background:#edf7ff;
 display:flex;align-items:center;justify-content:center;
 position:relative;padding:10px
}
.image-box img{
 width:100%;height:100%;object-fit:contain
}

.stock-badge{
 position:absolute;top:10px;left:10px;
 background:#16b66a;color:white;padding:6px 10px;
 border-radius:20px;font-size:12px
}

.card-body{padding:15px}
.card h3{margin:0 0 7px;color:#073c7d}
.category,.expiry{color:#718096;font-size:13px}
.price{color:#0567c9;font-size:20px;font-weight:bold;margin:10px 0}
.stock{color:#08a85c}

.tag{
 display:inline-block;padding:6px 9px;border-radius:15px;
 font-size:11px;background:#d9f7e9;color:#07834a;margin:10px 0
}
.required{background:#fff0c9;color:#b76a00}

.btn{
 display:block;padding:11px;border-radius:7px;
 background:#0879df;color:white;text-align:center;
 text-decoration:none;font-weight:bold;transition:.3s
}
.btn:hover{background:#075ca5;transform:scale(1.03)}

@media(max-width:1000px){.grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:750px){
 .grid{grid-template-columns:repeat(2,1fr)}
 .features{grid-template-columns:1fr}
 header{flex-wrap:wrap;gap:10px}
}
@media(max-width:500px){.grid{grid-template-columns:1fr}}
</style>
</head>

<body>

<header>

<div class="logo">
<img src="../images/logo_bg.png"> E-Pharmacy
</div>

<div class="search">
<input id="search" placeholder="🔍 Search medicines..."
onkeyup="searchMedicine()">
</div>

<nav>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="medicines.php">💊 Medicines</a>
<a href="cart.php">🛒 Cart</a>
<a href="orders.php">📦 Orders</a>
<a href="../auth/logout.php">🚪 Logout</a>
</nav>

</header>

<div class="container">

<div class="hero">
<small>SHOP MEDICINES</small>
<h1>Browse <span>Medicines</span></h1>
<p>Find the medicines you need. Safe and reliable.</p>
</div>

<div class="features">
<div class="feature">🛡️<b>100% Genuine</b><small>Authentic products</small></div>
<div class="feature">🔒<b>Secure Payments</b><small>Safe payment options</small></div>
<div class="feature">🎧<b>24/7 Support</b><small>We are always here</small></div>
</div>

<div class="grid">

<?php foreach($medicines as $m): ?>

<div class="card medicine-card">

<div class="image-box">

<span class="stock-badge">✓ In Stock</span>

<?php if(!empty($m['image'])): ?>

<img src="../images/medicines/<?=htmlspecialchars($m['image'])?>"
alt="<?=htmlspecialchars($m['name'])?>">

<?php else: ?>

<span style="font-size:55px">💊</span>

<?php endif; ?>

</div>

<div class="card-body">

<h3><?=htmlspecialchars($m['name'])?></h3>

<div class="category">
💊 <?=htmlspecialchars($m['category'])?>
</div>

<div class="price">
Rs. <?=number_format($m['price'],2)?>
</div>

<div class="expiry">
<span class="stock">🟢 Stock: <?=$m['stock']?></span>
&nbsp; 📅 <?=$m['expiry']?>
</div>

<?php if($m['prescription_required']): ?>

<span class="tag required">📋 Prescription Required</span>

<?php else: ?>

<span class="tag">✓ No Prescription Required</span>

<?php endif; ?>

<a class="btn" href="medicine_details.php?id=<?=$m['id']?>">
View Details →
</a>

</div>
</div>

<?php endforeach; ?>

</div>
</div>

<script>
function searchMedicine(){
 let x=document.getElementById("search").value.toLowerCase();
 document.querySelectorAll(".medicine-card").forEach(c=>{
  c.style.display=c.innerText.toLowerCase().includes(x)?"":"none";
 });
}
</script>

</body>
</html>