<?php
session_start();
require_once '../includes/auth-check.php';
require_once '../includes/role-check.php';
checkRole('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Session History</h1>
    <div class="mb-4">
        <label class="block text-sm font-medium">Filter by Week/Month</label>
        <select id="filter" class="p-2 border rounded-xl">
            <option value="all">All</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
        </select>
    </div>
    <div id="sessions">Loading...</div>
    <script>
        Date.prototype.getWeek = function() {
            const d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            return Math.ceil((((d - yearStart) / 86400000) + 1)/7);
        };

        async function loadSessions() {
            const res = await fetch('/api/sessions/list.php');
            let sessions = await res.json();
            const filter = document.getElementById('filter').value;
            if (filter === 'week') {
                const now = new Date();
                const week = now.getWeek();
                const year = now.getFullYear();
                sessions = sessions.filter(s => s.week_number == week && s.year == year);
            } else if (filter === 'month') {
                const now = new Date();
                const month = now.getMonth() + 1;
                const year = now.getFullYear();
                sessions = sessions.filter(s => {
                    const d = new Date(s.session_date);
                    return d.getMonth() + 1 === month && d.getFullYear() === year;
                });
            }
            const html = sessions.map(s => `
                <div class="bg-white rounded-2xl shadow-md p-4 mb-2">
                    <p><strong>${s.subject}</strong> - ${s.hours_taught}h on ${s.session_date}</p>
                    <p>Amount: ${s.amount_xaf} XAF</p>
                </div>
            `).join('');
            document.getElementById('sessions').innerHTML = html || 'No sessions found.';
        }
        document.getElementById('filter').addEventListener('change', loadSessions);
        loadSessions();
    </script>
</body>
</html>