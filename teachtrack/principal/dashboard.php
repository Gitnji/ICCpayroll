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
    <title>Principal Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Principal Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold">Total Hours This Month</h2>
            <p id="totalHours">Loading...</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold">Total Payroll This Month</h2>
            <p id="totalPayroll">Loading...</p>
        </div>
    </div>
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-4">Teachers Overview</h2>
        <div id="teachers">Loading...</div>
    </div>
    <div class="flex flex-wrap gap-4">
        <a href="manage-teachers.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Manage Teachers</a>
        <a href="payroll.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Payroll</a>
        <a href="flagged-logs.php" class="bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Flagged Logs</a>
    </div>
    <script>
        async function loadData() {
            const payrollRes = await fetch('/api/payroll/monthly.php');
            const payroll = await payrollRes.json();
            const totalHours = payroll.reduce((sum, t) => sum + t.total_hours, 0);
            const totalPay = payroll.reduce((sum, t) => sum + t.total_amount, 0);
            document.getElementById('totalHours').textContent = `${totalHours} hours`;
            document.getElementById('totalPayroll').textContent = `${totalPay.toLocaleString()} XAF`;

            const teachersRes = await fetch('/api/teachers/list.php');
            const teachers = await teachersRes.json();
            const teachersHtml = await Promise.all(teachers.map(async (t) => {
                const weekRes = await fetch(`/api/sessions/weekly-total.php?teacher_id=${t.id}`);
                const week = await weekRes.json();
                return `<div class="bg-white rounded-2xl shadow-md p-4">${t.full_name}: ${week.total_hours} / ${t.weekly_hour_cap} hours</div>`;
            }));
            document.getElementById('teachers').innerHTML = teachersHtml.join('');
        }
        loadData();
    </script>
</body>
</html>