<?php
require_once "../config/role_check.php";
requireRole(["pharmacist"]);
require_once "../config/database.php";

/* ADD MEDICINE */
if(isset($_POST['add'])){
    $image = NULL;

    if(!empty($_FILES['image']['name'])){
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if(in_array($ext,$allowed)){
            $image = uniqid().".".$ext;
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../images/medicines/".$image
            );
        }
    }

    $sql="INSERT INTO medicines
    (category_id,name,category,generic_name,description,manufacturer,
    price,stock,prescription_required,image,expiry_date,status)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,'available')";

    $stmt=$conn->prepare($sql);
    $stmt->execute([
        $_POST['category_id'],
        $_POST['name'],
        $_POST['category_name'],
        $_POST['name'],
        "Medicine available from E-Pharmacy",
        "E-Pharmacy",
        $_POST['price'],
        $_POST['stock'],
        $_POST['prescription'],
        $image,
        $_POST['expiry']
    ]);

    header("Location: inventory.php");
    exit;
}

/* REMOVE */
if(isset($_GET['delete'])){
    $stmt=$conn->prepare(
        "UPDATE medicines SET status='unavailable' WHERE id=?"
    );
    $stmt->execute([(int)$_GET['delete']]);
    header("Location: inventory.php");
    exit;
}

$categories=$conn->query(
    "SELECT * FROM categories ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);

$medicines=$conn->query(
    "SELECT * FROM medicines WHERE status='available' ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory - E-Pharmacy</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;font-family:Arial;
    background:#eef8ff url("../images/dashboard_bg.png") center/cover fixed;
    color:#12345b
}
header{
    background:#1976d2;color:white;padding:18px 6%;
    display:flex;align-items:center;box-shadow:0 3px 10px #0003
}
.logo{font-size:24px;font-weight:bold;margin-right:auto}
nav a{
    color:white;text-decoration:none;margin-left:20px;
    padding:10px 12px;border-radius:8px;transition:.3s
}
nav a:hover{background:white;color:#1976d2;transform:translateY(-3px)}

.box{
    width:92%;max-width:1200px;margin:35px auto;
    background:white;padding:25px;border-radius:15px;
    box-shadow:0 4px 15px #0002
}
input,select,button{
    padding:11px;margin:4px;border:1px solid #ddd;border-radius:6px
}
button{background:#1976d2;color:white;border:0;cursor:pointer}
button:hover{background:#125ca8}

table{width:100%;border-collapse:collapse;margin-top:25px}
th{background:#1976d2;color:white;padding:12px}
td{padding:10px;border-bottom:1px solid #ddd;text-align:center}
td img{width:60px;height:60px;object-fit:contain;border-radius:8px}
.delete{
    background:#e53935;color:white;padding:7px 10px;
    border-radius:5px;text-decoration:none
}
</style>
</head>

<body>

<header>
    <div class="logo">💊 E-Pharmacy</div>

    <nav>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="inventory.php">📦 Inventory</a>
        <a href="prescriptions.php">📋 Prescriptions</a>
        <a href="orders.php">🛒 Orders</a>
        <a href="../auth/logout.php">🚪 Logout</a>
    </nav>
</header>

<div class="box">

<h2>📦 Inventory Management</h2>

<form method="POST" enctype="multipart/form-data">

<input name="name" placeholder="Medicine Name" required>

<select name="category_id"
onchange="this.form.category_name.value=this.options[this.selectedIndex].text"
required>
<option value="">Category</option>

<?php foreach($categories as $c): ?>
<option value="<?=$c['id']?>">
<?=htmlspecialchars($c['name'])?>
</option>
<?php endforeach; ?>

</select>

<input type="hidden" name="category_name">

<input name="stock" type="number" min="0"
placeholder="Stock" required>

<input name="price" type="number" step=".01"
placeholder="Price" required>

<input name="expiry" type="date" required>

<select name="prescription">
<option value="0">No Prescription</option>
<option value="1">Prescription Required</option>
</select>

<input type="file" name="image"
accept=".jpg,.jpeg,.png,.webp" required>

<button name="add">＋ Add Medicine</button>

</form>

<table>

<tr>
<th>Image</th>
<th>Medicine</th>
<th>Category</th>
<th>Stock</th>
<th>Price</th>
<th>Expiry</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach($medicines as $m): ?>

<tr>

<td>
<?php if($m['image']): ?>
<img src="../images/medicines/<?=htmlspecialchars($m['image'])?>">
<?php else: ?>
💊
<?php endif; ?>
</td>

<td><b><?=htmlspecialchars($m['name'])?></b></td>

<td><?=htmlspecialchars($m['category'])?></td>

<td><?=$m['stock']?></td>

<td>Rs. <?=number_format($m['price'],2)?></td>

<td><?=$m['expiry_date']?></td>

<td>
<?=$m['stock']<=10 ? "🟠 Low" : "🟢 In Stock"?>
</td>

<td>
<a class="delete"
href="?delete=<?=$m['id']?>"
onclick="return confirm('Remove this medicine?')">
Remove
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>
</body>
</html>