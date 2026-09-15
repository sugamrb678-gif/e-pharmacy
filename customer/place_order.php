<?php

require_once "../config/role_check.php";
requireRole(["customer"]);

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

/* Get cart */
$sql = "SELECT
            cart.medicine_id,
            cart.quantity,
            medicines.name,
            medicines.price,
            medicines.stock,
            medicines.prescription_required
        FROM cart
        JOIN medicines
        ON cart.medicine_id = medicines.id
        WHERE cart.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    header("Location: cart.php");
    exit;
}


/* Check prescription requirement */

$needs_prescription = false;

foreach ($cart_items as $item) {

    if ($item["prescription_required"]) {
        $needs_prescription = true;
        break;
    }
}


/* Get approved prescription */

$prescription_id = null;

if ($needs_prescription) {

    $sql = "SELECT id
            FROM prescriptions
            WHERE user_id = ?
            AND status = 'approved'
            ORDER BY id DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);

    $prescription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prescription) {
        die(
            "<h2>Prescription Required</h2>
             <p>You need an approved prescription before ordering these medicines.</p>
             <a href='prescriptions.php'>Upload Prescription</a>"
        );
    }

    $prescription_id = $prescription["id"];
}


/* Calculate total */

$total = 0;

foreach ($cart_items as $item) {

    if ($item["quantity"] > $item["stock"]) {
        die(
            "Not enough stock for " .
            htmlspecialchars($item["name"])
        );
    }

    $total +=
        $item["price"] *
        $item["quantity"];
}


try {

    $conn->beginTransaction();


    /* Create order */

    $sql = "INSERT INTO orders
            (user_id, prescription_id, total_amount)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $user_id,
        $prescription_id,
        $total
    ]);

    $order_id = $conn->lastInsertId();


    /* Add order items */

    foreach ($cart_items as $item) {

        $subtotal =
            $item["price"] *
            $item["quantity"];


        $sql = "INSERT INTO order_items
                (order_id, medicine_id, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            $order_id,
            $item["medicine_id"],
            $item["quantity"],
            $item["price"],
            $subtotal
        ]);


        /* Reduce stock */

        $sql = "UPDATE medicines
                SET stock = stock - ?
                WHERE id = ?
                AND stock >= ?";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            $item["quantity"],
            $item["medicine_id"],
            $item["quantity"]
        ]);

        if ($stmt->rowCount() == 0) {
            throw new Exception(
                "Stock update failed for " .
                $item["name"]
            );
        }
    }


    /* Clear cart */

    $stmt = $conn->prepare(
        "DELETE FROM cart WHERE user_id = ?"
    );

    $stmt->execute([$user_id]);


    /* Finish */

    $conn->commit();


    header(
        "Location: order_success.php?id=" .
        $order_id
    );

    exit;


} catch (Exception $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    die(
        "Order failed: " .
        htmlspecialchars($e->getMessage())
    );
}

?>