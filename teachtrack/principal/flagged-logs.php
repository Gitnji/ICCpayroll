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
    <title>Flagged Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Flagged Logs</h1>
    <div id="flaggedLogs">Loading...</div>
    <script>
        async function loadFlagged() {
            // Assuming an API for flagged logs, but for now, fetch all blocked
            const res = await fetch('/api/sessions/list.php'); // Need to modify to filter blocked
            const sessions = await res.json();
            const flagged = sessions.filter(s => s.status === 'blocked');
            const html = flagged.map(s => `
                <div class="bg-white rounded-2xl shadow-md p-4 mb-2">
                    <p><strong>${s.subject}</strong> - ${s.hours_taught}h on ${s.session_date}</p>
                    <p>Teacher: ${s.teacher_id} (blocked)</p>
                </div>
            `).join('');
            document.getElementById('flaggedLogs').innerHTML = html || 'No flagged logs.';
        }
        loadFlagged();
    </script>
</body>
</html>