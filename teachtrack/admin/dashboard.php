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
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    <p>Full system overview across all schools (future-ready)</p>
    <a href="manage-schools.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800 inline-block">Manage Schools</a>
    <a href="manage-principals.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800 inline-block ml-4">Manage Principals</a>
</body>
</html>