<?php
require_once "../config/role_check.php";
requireRole(["customer"]);
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];
$message = "";

$selected_medicine_id = (int)($_GET["id"] ?? $_POST["medicine_id"] ?? 0);

/* Get medicines that require prescription */
$stmt = $conn->query("
    SELECT id, name
    FROM medicines
    WHERE status='available'
    AND stock > 0
    AND prescription_required=1
    ORDER BY name
");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $medicine_id = (int)($_POST["medicine_id"] ?? 0);
    $file = $_FILES["prescription"] ?? null;

    if (!$medicine_id) {
        $message = "Please select a medicine.";
    } elseif (!$file || $file["error"] != 0) {
        $message = "Please select a prescription file.";
    } else {

        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "pdf"];

        if (!in_array($ext, $allowed)) {
            $message = "Only JPG, PNG and PDF allowed.";
        } else {

            $dir = "../uploads/prescriptions/";

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $filename = time() . "_" . uniqid() . "." . $ext;

            if (move_uploaded_file($file["tmp_name"], $dir . $filename)) {

                /* Create prescription */
                $stmt = $conn->prepare("
                    INSERT INTO prescriptions
                    (user_id, prescription_file, status)
                    VALUES (?, ?, 'pending')
                ");

                $stmt->execute([$user_id, $filename]);

                /* Get new prescription ID */
                $prescription_id = $conn->lastInsertId();

                /* Connect prescription to THIS medicine */
                $stmt = $conn->prepare("
                    INSERT INTO prescription_items
                    (prescription_id, medicine_id, quantity)
                    VALUES (?, ?, 1)
                ");

                $stmt->execute([
                    $prescription_id,
                    $medicine_id
                ]);

                header("Location: my_prescriptions.php");
                exit;
            }

            $message = "Upload failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Prescription</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:url("../images/dashboard_bg.png") center/cover fixed;
}

.box{
    max-width:450px;
    margin:60px auto;
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 3px 12px #ccc;
}

h2{
    color:#1976d2;
}

input,select,button{
    width:100%;
    padding:12px;
    margin-top:15px;
    box-sizing:border-box;
}

button{
    background:#1976d2;
    color:white;
    border:0;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#125ca1;
}

.msg{
    color:red;
    margin:10px 0;
}

.nav-btn{
    background:white;
    color:#1976d2;
    padding:10px 15px;
    border-radius:7px;
    text-decoration:none;
    font-weight:bold;
}

.nav-btn:hover{
    background:#e3f2fd;
}
</style>
</head>

<body>

<div class="box">

<h2>📋 Upload Prescription</h2>

<?php if($message): ?>
<div class="msg"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Select Medicine:</label>

<select name="medicine_id" required>
    <option value="">-- Select Medicine --</option>

    <?php foreach($medicines as $m): ?>
       <option value="<?= $m["id"] ?>"
    <?= $selected_medicine_id == $m["id"] ? "selected" : "" ?>>
    <?= htmlspecialchars($m["name"]) ?>
</option> 
    <?php endforeach; ?>

</select>

<input type="file"
       name="prescription"
       accept=".jpg,.jpeg,.png,.pdf"
       required>

<button type="submit">📤 Upload Prescription</button>

</form>

<br>

<nav>
    <a href="dashboard.php" class="nav-btn">🏠 Dashboard</a>
    <a href="my_prescriptions.php" class="nav-btn">📋 My Prescriptions</a>
</nav>

</div>

</body>
</html>