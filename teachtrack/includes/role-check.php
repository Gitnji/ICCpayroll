<?php
function checkRole($requiredRole) {
    if ($_SESSION['role'] !== $requiredRole) {
        header('Location: /index.php');
        exit;
    }
}
?>