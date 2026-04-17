<?php
session_start();
require_once '../includes/auth-check.php';
require_once '../includes/role-check.php';
checkRole('principal');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Manage Classes</h1>
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Add Class</h2>
        <form id="addForm">
            <div class="mb-4">
                <label class="block text-sm font-medium">Class Name</label>
                <input type="text" id="name" class="w-full p-2 border rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Hourly Rate</label>
                <select id="hourly_rate" class="w-full p-2 border rounded-xl" required>
                    <option value="500">500 XAF</option>
                    <option value="700">700 XAF</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Add Class</button>
        </form>
    </div>
    <div>
        <h2 class="text-lg font-semibold mb-4">Classes List</h2>
        <div id="classes">Loading...</div>
    </div>
    <script>
        async function loadClasses() {
            const res = await fetch('/api/classes/list.php');
            const classes = await res.json();
            const html = classes.map(c => `
                <div class="bg-white rounded-2xl shadow-md p-4 mb-2">
                    <p><strong>${c.name}</strong></p>
                    <p>Rate: ${c.hourly_rate} XAF/h</p>
                </div>
            `).join('');
            document.getElementById('classes').innerHTML = html;
        }

        document.getElementById('addForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                name: document.getElementById('name').value,
                hourly_rate: document.getElementById('hourly_rate').value
            };
            const res = await fetch('/api/classes/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                alert('Class added!');
                loadClasses();
            } else {
                alert(result.message);
            }
        });

        loadClasses();
    </script>
</body>
</html>