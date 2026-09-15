<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config/role_check.php";
requireRole(["pharmacist"]);

require_once "../config/database.php";


/* Make sure prescription ID was submitted */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: prescriptions.php");
    exit;
}

$prescription_id = $_POST['prescription_id'] ?? null;

if (!$prescription_id) {
    header("Location: prescriptions.php");
    exit;
}


/* Get pharmacist ID from session */

$pharmacist_id = $_SESSION['user_id'] ?? null;


/* Reject prescription */

$sql = "
    UPDATE prescriptions
    SET
        status = 'rejected',
        pharmacist_id = :pharmacist_id,
        pharmacist_note = 'Prescription rejected by pharmacist.',
        reviewed_at = CURRENT_TIMESTAMP
    WHERE id = :id
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':pharmacist_id' => $pharmacist_id,
    ':id' => $prescription_id
]);


/* Return to prescriptions page */

header("Location: prescriptions.php");
exit;

?>

