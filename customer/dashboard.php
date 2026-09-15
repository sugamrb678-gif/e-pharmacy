<?php
require_once "../config/role_check.php";
requireRole(["customer"]);
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard - E-Pharmacy</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:Arial;
    background:url("../images/dashboard_bg.png") center/cover fixed;
}

header{
    height:100px;
    background:linear-gradient(90deg,#2949c7,#4931b5);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 5%;
    color:white;
    box-shadow:0 3px 12px #555;
}

.logo{
    display:flex;
    align-items:center;
    font-size:27px;
    font-weight:bold;
}

.logo img{
    width:50px;
    height:50px;
    object-fit:contain;
    margin-right:10px;
}

nav{
    display:flex;
    gap:32px;
}

nav a{
    color:white;
    text-decoration:none;
    text-align:center;
    font-weight:bold;
    font-size:16px;
    transition:.2s;
}

nav span{
    display:block;
    font-size:27px;
    margin-bottom:5px;
}

nav a:hover{
    transform:translateY(-3px);
    color:#dbeafe;
}

.container{
    max-width:1200px;
    margin:40px auto;
    padding:20px;
}

.welcome{
    background:#ffffffee;
    border-radius:25px;
    padding:35px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 5px 20px #5555;
    margin-bottom:30px;
}

.welcome h1{
    margin:0;
    color:#172554;
    font-size:35px;
}

.welcome p{
    color:#555;
    font-size:17px;
}

.welcome-icon{
    font-size:80px;
}

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:22px;
}

.card{
    background:#fffffff2;
    padding:28px 20px;
    text-align:center;
    border-radius:20px;
    text-decoration:none;
    color:#333;
    box-shadow:0 5px 18px #5555;
    transition:.3s;
}

.card:hover{
    transform:translateY(-7px);
}

.icon{
    width:70px;
    height:70px;
    margin:auto;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
}

.card h2{
    margin:15px 0 8px;
}

.card p{
    color:#666;
}

.medicine .icon{background:#ffd9e2}
.medicine h2{color:#e91e63}

.pres .icon{background:#eadcff}
.pres h2{color:#673ab7}

.cart .icon{background:#ffe2b8}
.cart h2{color:#f57c00}

.orders .icon{background:#c9f5f7}
.orders h2{color:#0097a7}

.arrow{
    display:inline-block;
    background:#1976d2;
    color:white;
    padding:10px 15px;
    border-radius:50%;
    font-size:20px;
}

@media(max-width:900px){
    .cards{grid-template-columns:1fr 1fr}
}

@media(max-width:650px){
    header{
        height:auto;
        padding:15px;
        display:block;
        text-align:center;
    }

    nav{
        justify-content:center;
        margin-top:15px;
        gap:18px;
        flex-wrap:wrap;
    }

    .cards{grid-template-columns:1fr}

    .welcome-icon{display:none}
}
</style>
</head>

<body>

<header>

<div class="logo">
    <img src="../images/logo_bg.png">
    E-Pharmacy
</div>

<nav>
    <a href="../index.php">
        <span>🏠 </span>Home
    </a>

    <a href="medicines.php">
        <span>💊</span>Medicines
    </a>

    <a href="cart.php">
        <span>🛒</span>Cart
    </a>

    <a href="my_prescriptions.php">
        <span>📋</span>Prescriptions
    </a>

    <a href="../auth/logout.php">
        <span>↪</span>Logout
    </a>
</nav>

</header>

<div class="container">

<div class="welcome">

<div>
    <h1>
        Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>! 👋
    </h1>

    <p>Your one-stop solution for all your medical needs.</p>

    <p>Browse medicines, manage prescriptions and track your orders.</p>
</div>

<div class="welcome-icon">🛍️</div>

</div>

<div class="cards">

<a href="medicines.php" class="card medicine">
    <div class="icon">💊</div>
    <h2>Medicines</h2>
    <p>Browse available medicines.</p>
    <span class="arrow">→</span>
</a>

<a href="my_prescriptions.php" class="card pres">
    <div class="icon">📋</div>
    <h2>Prescriptions</h2>
    <p>Upload and manage prescriptions.</p>
    <span class="arrow">→</span>
</a>

<a href="cart.php" class="card cart">
    <div class="icon">🛒</div>
    <h2>Shopping Cart</h2>
    <p>View medicines in your cart.</p>
    <span class="arrow">→</span>
</a>

<a href="orders.php" class="card orders">
    <div class="icon">📦</div>
    <h2>My Orders</h2>
    <p>View and track your orders.</p>
    <span class="arrow">→</span>
</a>

</div>

</div>

</body>
</html>