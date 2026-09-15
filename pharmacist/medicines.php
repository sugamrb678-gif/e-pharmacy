<?php
$c=new mysqli("localhost","root","","e_pharmacy");

if(isset($_POST['add'])){
    $n=$_POST['name'];
    $cat=$_POST['category'];
    $d=$_POST['description'];
    $p=$_POST['price'];

    $c->query("INSERT INTO medicines(name,category,description,price)
               VALUES('$n','$cat','$d','$p')");
    header("Location: medicines.php");
    exit;
}

$data=$c->query("SELECT * FROM medicines ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Medicines - E-Pharmacy</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:Arial;
    background:#eef8ff url("../images/dashboard_bg.png") center/cover fixed;
    color:#12345b;
}

/* NAVBAR */
nav{
    height:70px;
    padding:0 40px;
    background:linear-gradient(135deg,#1976d2,#0d47a1);
    display:flex;
    align-items:center;
    box-shadow:0 3px 12px #555;
}

.logo{
    color:white;
    font-size:24px;
    font-weight:bold;
    margin-right:auto;
}

nav a{
    color:white;
    text-decoration:none;
    padding:11px 15px;
    margin-left:8px;
    border-radius:8px;
    transition:.3s;
}

nav a:hover{
    background:white;
    color:#1976d2;
    transform:translateY(-3px);
    box-shadow:0 4px 10px #0003;
}

.box{
    width:92%;
    max-width:1200px;
    margin:35px auto;
}

.add,.table{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px #0002;
    margin-bottom:25px;
}

h1{margin-top:0;color:#1565c0}

input{
    padding:12px;
    margin:5px;
    border:1px solid #ddd;
    border-radius:7px;
}

button{
    padding:12px 20px;
    background:#1976d2;
    color:white;
    border:0;
    border-radius:7px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#0d47a1;
    transform:translateY(-2px);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#1976d2;
    color:white;
    padding:14px;
    text-align:left;
}

td{
    padding:14px;
    border-bottom:1px solid #ddd;
}

tr:hover{background:#f5faff}

.price{
    color:#1976d2;
    font-weight:bold;
}

@media(max-width:700px){
    nav{padding:10px;flex-wrap:wrap;height:auto}
    nav a{font-size:13px}
    input{width:100%;margin:5px 0}
}
</style>
</head>

<body>

<nav>
    <div class="logo">💊 E-Pharmacy</div>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="inventory.php">📦 Inventory</a>
    <a href="prescriptions.php">📋 Prescriptions</a>
    <a href="orders.php">🛒 Orders</a>
    <a href="../auth/logout.php">🚪 Logout</a>
</nav>

<div class="box">

<div class="add">
    <h1>💊 Medicines</h1>
    <p>View and add medicines available in the pharmacy.</p>

    <form method="POST">
        <input name="name" placeholder="Medicine Name" required>
        <input name="category" placeholder="Category" required>
        <input name="description" placeholder="Description">
        <input name="price" type="number" step=".01" placeholder="Price" required>
        <button name="add">＋ Add Medicine</button>
    </form>
</div>

<div class="table">

<table>
<tr>
    <th>Medicine</th>
    <th>Category</th>
    <th>Description</th>
    <th>Price</th>
</tr>

<?php while($r=$data->fetch_assoc()){ ?>

<tr>
    <td><b><?=htmlspecialchars($r['name'])?></b></td>
    <td><?=htmlspecialchars($r['category'])?></td>
    <td><?=htmlspecialchars($r['description'])?></td>
    <td class="price">Rs. <?=number_format($r['price'],2)?></td>
</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>