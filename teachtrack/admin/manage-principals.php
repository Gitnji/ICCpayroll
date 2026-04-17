<?php
session_start();
require_once '../includes/auth-check.php';
require_once '../includes/role-check.php';
checkRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Principals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Manage Principals</h1>
    <p>Add/manage principal accounts (future implementation)</p>
</body>
</html>