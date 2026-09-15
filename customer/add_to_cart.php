```php
<?php

require_once "../config/role_check.php";
requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];
$medicine_id = (int)($_GET["id"] ?? 0);

if ($medicine_id <= 0) {
    die("Invalid medicine ID.");
}

/* Medicine */
$stmt = $conn->prepare("
    SELECT id, name, price, stock, status, prescription_required
    FROM medicines
    WHERE id = ?
");
$stmt->execute([$medicine_id]);

$medicine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicine) {
    die("Medicine not found.");
}

if ($medicine["status"] !== "available") {
    die("Medicine is unavailable.");
}

if ((int)$medicine["stock"] <= 0) {
    die("Medicine is out of stock.");
}

/* Prescription check */
if ((int)$medicine["prescription_required"] === 1) {

    $stmt = $conn->prepare("
        SELECT id
        FROM prescriptions
        WHERE user_id = ?
        AND status = 'approved'
        LIMIT 1
    ");

    $stmt->execute([$user_id]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        die("Please upload an approved prescription first.");
    }

}

/* Check cart */
$stmt = $conn->prepare("
    SELECT id, quantity
    FROM cart
    WHERE user_id = ? AND medicine_id = ?
");

$stmt->execute([$user_id, $medicine_id]);

$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {

    $quantity = $item["quantity"] + 1;

    if ($quantity > $medicine["stock"]) {
        $quantity = $medicine["stock"];
    }

    $stmt = $conn->prepare("
        UPDATE cart
        SET quantity = ?
        WHERE id = ?
    ");

    $stmt->execute([$quantity, $item["id"]]);

} else {

    $stmt = $conn->prepare("
        INSERT INTO cart (user_id, medicine_id, quantity)
        VALUES (?, ?, 1)
    ");

    $stmt->execute([$user_id, $medicine_id]);
}

/* Go to cart */
header("Location: cart.php");
exit;

?>
```
