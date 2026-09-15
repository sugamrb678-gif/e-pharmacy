<?php
require_once "../config/role_check.php";
requireRole(["pharmacist"]);
require_once "../config/database.php";

if (isset($_POST["id"])) {

    $id = (int)$_POST["id"];

    $stmt = $conn->prepare(
        "SELECT prescription_file FROM prescriptions WHERE id=?"
    );
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    if ($p) {
        $file = "../uploads/prescriptions/" . basename($p["prescription_file"]);

        if (file_exists($file)) {
            unlink($file);
        }

        $stmt = $conn->prepare("DELETE FROM prescriptions WHERE id=?");
        $stmt->execute([$id]);
    }
}

header("Location: prescriptions.php");
exit;
?>