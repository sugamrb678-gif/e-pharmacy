<?php

require_once "../config/role_check.php";

requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

// Check cart ID and quantity
if (
    !isset($_POST["cart_id"]) ||
    !isset($_POST["quantity"]) ||
    !is_numeric($_POST["cart_id"]) ||
    !is_numeric($_POST["quantity"])
) {
    header("Location: cart.php");
    exit;
}

$cart_id = (int) $_POST["cart_id"];
$quantity = (int) $_POST["quantity"];

// Minimum quantity is 1
if ($quantity < 1) {
    $quantity = 1;
}

// Get cart item and medicine stock
$sql = "SELECT cart.id, medicines.stock
        FROM cart
        INNER JOIN medicines
        ON cart.medicine_id = medicines.id
        WHERE cart.id = ?
        AND cart.user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $cart_id,
    $user_id
]);

$item = $stmt->fetch();

// Cart item not found
if (!$item) {
    header("Location: cart.php");
    exit;
}

// Don't allow quantity greater than stock
if ($quantity > $item["stock"]) {
    $quantity = $item["stock"];
}

// Update cart
$sql = "UPDATE cart
        SET quantity = ?
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $quantity,
    $cart_id,
    $user_id
]);

// Return to cart
header("Location: cart.php");
exit;

?>