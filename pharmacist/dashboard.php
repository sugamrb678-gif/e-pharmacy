<?php
require_once "../config/role_check.php";
requireRole(["pharmacist"]);
$name=$_SESSION["user_name"]??"Pharmacist";
?>

<!DOCTYPE html>
<html>
<head>
<title>Pharmacist Dashboard</title>
<link rel="stylesheet" href="../css/style.css">

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
*{box-sizing:border-box}

body{
    margin:0;
    font-family:Arial;
    background:url("../images/dashboard_bg.png") center/cover fixed no-repeat;
}

header{
    height:70px;
    padding:0 35px;
    background:#1976d2;
    color:white;
    display:flex;
    align-items:center;
    box-shadow:0 2px 8px #555;
}

.logo{
    font-size:25px;
    font-weight:bold;
    margin-right:auto;
}

nav{
    display:flex;
    gap:8px;
}

nav a{
    color:white;
    text-decoration:none;
    padding:10px 13px;
    border-radius:8px;
    transition:.25s;
}

nav a:hover{
    background:white;
    color:#1976d2;
    transform:translateY(-3px);
}

.logout{
    background:#e53935;
}

.logout:hover{
    background:#c62828;
    color:white;
}

.container{
    width:90%;
    max-width:1200px;
    margin:45px auto;
}

.welcome,.card{
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 3px 12px #aaa;
}

.welcome{
    margin-bottom:25px;
}

.welcome h1{
    margin:0 0 10px;
}

.welcome p{
    color:#666;
}

.cards{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:25px;
}

.card{
    transition:.25s;
}

.card:hover{
    transform:translateY(-5px);
}

.card-icon{
    font-size:40px;
}

.card h2{
    margin:10px 0;
}

.card p{
    color:#666;
    line-height:1.5;
}

.btn{
    display:inline-block;
    background:#1976d2;
    color:white;
    padding:11px 18px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
}

.btn:hover{
    background:#125ca8;
}

footer{
    margin-top:60px;
    background:#1976d2;
    color:white;
    text-align:center;
    padding:18px;
}

@media(max-width:800px){
    nav{gap:2px}
    nav a{padding:8px}
    .cards{grid-template-columns:1fr}
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
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="prescriptions.php">📋 Prescriptions</a>
        <a href="medicines.php">💊 Medicines</a>
        <a href="inventory.php">📦 Inventory</a>
        <a href="orders.php">🛒 Orders</a>
        <a href="../auth/logout.php" class="logout">🚪 Logout</a>
    </nav>
</header>

<main class="container">

    <div class="welcome">
        <h1>👨‍⚕️ Pharmacist Dashboard</h1>
        <p>Welcome, <b><?=htmlspecialchars($name)?></b>.</p>
        <p>Manage prescriptions, medicines, inventory and customer orders.</p>
    </div>

    <section class="cards">

        <div class="card">
            <div class="card-icon">📋</div>
            <h2>Prescription Management</h2>
            <p>View and review customer prescriptions.</p>
            <a href="prescriptions.php" class="btn">View Prescriptions</a>
        </div>

        <div class="card">
            <div class="card-icon">💊</div>
            <h2>Medicines</h2>
            <p>View available medicines and information.</p>
            <a href="medicines.php" class="btn">View Medicines</a>
        </div>

        <div class="card">
            <div class="card-icon">📦</div>
            <h2>Inventory</h2>
            <p>Manage stock, price and expiry dates.</p>
            <a href="inventory.php" class="btn">View Inventory</a>
        </div>

        <div class="card">
            <div class="card-icon">🛒</div>
            <h2>Customer Orders</h2>
            <p>View and manage customer orders.</p>
            <a href="orders.php" class="btn">View Orders</a>
        </div>

    </section>
</main>

<footer>
    © 2026 E-Pharmacy. All rights reserved.
</footer>

</body>
</html>