<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachTrack - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-md p-6 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">TeachTrack</h1>
        <form id="loginForm">
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" id="email" class="w-full p-2 border rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input type="password" id="password" class="w-full p-2 border rounded-xl" required>
            </div>
            <button type="submit" class="w-full bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Login</button>
        </form>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            const result = await response.json();
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                alert(result.message);
            }
        });
    </script>
</body>
</html>