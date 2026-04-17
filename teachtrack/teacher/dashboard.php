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
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Welcome, <?php echo $_SESSION['full_name']; ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold">Hours This Week</h2>
            <p id="hoursWeek">Loading...</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold">Expected Pay This Month</h2>
            <p id="payMonth">Loading...</p>
        </div>
    </div>
    <a href="log-session.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800 inline-block">Log a Session</a>
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Recent Sessions</h2>
        <div id="recentSessions">Loading...</div>
    </div>
    <script>
        async function loadData() {
            const [weekRes, sessionsRes] = await Promise.all([
                fetch('/api/sessions/weekly-total.php'),
                fetch('/api/sessions/list.php')
            ]);
            const week = await weekRes.json();
            const sessions = await sessionsRes.json();
            document.getElementById('hoursWeek').textContent = `${week.total_hours} / <?php echo $_SESSION['weekly_hour_cap']; ?> hours`;
            const thisMonth = new Date().getMonth() + 1;
            const thisYear = new Date().getFullYear();
            const monthSessions = sessions.filter(s => {
                const d = new Date(s.session_date);
                return d.getMonth() + 1 === thisMonth && d.getFullYear() === thisYear;
            });
            const totalPay = monthSessions.reduce((sum, s) => sum + s.amount_xaf, 0);
            document.getElementById('payMonth').textContent = `${totalPay.toLocaleString()} XAF`;
            const recent = sessions.slice(0, 5).map(s => `<div>${s.subject} - ${s.hours_taught}h on ${s.session_date}</div>`).join('');
            document.getElementById('recentSessions').innerHTML = recent || 'No sessions yet';
        }
        loadData();
    </script>
</body>
</html>