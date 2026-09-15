<?php
$conn = new mysqli("localhost", "root", "", "e_pharmacy");

if ($conn->connect_error) {
    die("Connection failed");
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM inventory WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiry = $_POST['expiry'];

    $conn->query("UPDATE inventory SET
        name='$name',
        quantity='$quantity',
        price='$price',
        expiry='$expiry'
        WHERE id=$id");

    header("Location: inventory.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Inventory</title>

    <style>
        body {
            font-family: Arial;
            background: #eef7fc;
        }

        .box {
            width: 450px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background: #1976d2;
            color: white;
            border: 0;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        a {
            margin-left: 10px;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>✏️ Edit Medicine</h2>

    <form method="POST">

        <input type="text"
               name="name"
               value="<?= htmlspecialchars($row['name']) ?>"
               required>

        <input type="number"
               name="quantity"
               value="<?= $row['quantity'] ?>"
               min="0"
               required>

        <input type="number"
               name="price"
               value="<?= $row['price'] ?>"
               step="0.01"
               min="0"
               required>

        <input type="date"
               name="expiry"
               value="<?= $row['expiry'] ?>"
               required>

        <button name="update">
            Update Medicine
        </button>

        <a href="inventory.php">
            Cancel
        </a>

    </form>

</div>

</body>
</html>