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
    <title>Manage Teachers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Manage Teachers</h1>
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Add Teacher</h2>
        <form id="addForm">
            <div class="mb-4">
                <label class="block text-sm font-medium">Full Name</label>
                <input type="text" id="full_name" class="w-full p-2 border rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" id="email" class="w-full p-2 border rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input type="password" id="password" class="w-full p-2 border rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Weekly Hour Cap</label>
                <input type="number" id="weekly_hour_cap" value="8" class="w-full p-2 border rounded-xl" required>
            </div>
            <button type="submit" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Add Teacher</button>
        </form>
    </div>
    <div>
        <h2 class="text-lg font-semibold mb-4">Teachers List</h2>
        <div id="teachers">Loading...</div>
    </div>
    <script>
        async function loadTeachers() {
            const res = await fetch('/api/teachers/list.php');
            const teachers = await res.json();
            const html = teachers.map(t => `
                <div class="bg-white rounded-2xl shadow-md p-4 mb-2 flex justify-between items-center">
                    <div>
                        <p><strong>${t.full_name}</strong></p>
                        <p>${t.email}</p>
                        <p>Cap: <span id="cap-${t.id}">${t.weekly_hour_cap}</span> hours</p>
                    </div>
                    <button onclick="editCap(${t.id})" class="bg-blue-700 text-white rounded-xl px-4 py-2 hover:bg-blue-800">Edit Cap</button>
                </div>
            `).join('');
            document.getElementById('teachers').innerHTML = html;
        }

        document.getElementById('addForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                full_name: document.getElementById('full_name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                weekly_hour_cap: document.getElementById('weekly_hour_cap').value
            };
            const res = await fetch('/api/teachers/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                alert('Teacher added!');
                loadTeachers();
            } else {
                alert(result.message);
            }
        });

        async function editCap(id) {
            const newCap = prompt('Enter new weekly hour cap:');
            if (newCap) {
                const res = await fetch('/api/teachers/update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, weekly_hour_cap: newCap })
                });
                const result = await res.json();
                if (result.success) {
                    document.getElementById(`cap-${id}`).textContent = newCap;
                }
            }
        }

        loadTeachers();
    </script>
</body>
</html>