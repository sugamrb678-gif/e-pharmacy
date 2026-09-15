<?php

require_once "../config/role_check.php";

requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// Check cart ID
if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {
    header("Location: cart.php");
    exit;
}

$cart_id = (int) $_GET["id"];


// Delete only this user's cart item
$sql = "DELETE FROM cart
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $cart_id,
    $user_id
]);


// Return to cart
header("Location: cart.php");
exit;

?>