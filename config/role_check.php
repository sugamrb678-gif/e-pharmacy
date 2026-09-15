<?php

require_once "auth_check.php";

function requireRole($allowedRoles)
{
    if (!in_array(strtolower($_SESSION["user_role"]), array_map("strtolower", $allowedRoles))) {
        header("Location: ../index.php");
        exit;
    }
}
?>