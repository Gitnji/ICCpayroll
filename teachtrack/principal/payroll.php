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
    <title>Payroll</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Monthly Payroll</h1>
    <div class="mb-4">
        <label class="block text-sm font-medium">Month</label>
        <input type="month" id="month" value="<?php echo date('Y-m'); ?>" class="p-2 border rounded-xl">
        <button onclick="loadPayroll()" class="bg-blue-700 text-white rounded-xl px-4 py-2 hover:bg-blue-800 ml-2">Load</button>
    </div>
    <table class="w-full bg-white rounded-2xl shadow-md">
        <thead>
            <tr class="border-b">
                <th class="p-4 text-left">Teacher Name</th>
                <th class="p-4 text-left">Total Hours</th>
                <th class="p-4 text-left">Amount Owed (XAF)</th>
            </tr>
        </thead>
        <tbody id="payrollBody">Loading...</tbody>
    </table>
    <button onclick="window.print()" class="mt-4 bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Export to PDF</button>
    <script>
        async function loadPayroll() {
            const month = document.getElementById('month').value;
            const [year, mon] = month.split('-');
            const res = await fetch(`/api/payroll/monthly.php?month=${mon}&year=${year}`);
            const payroll = await res.json();
            const html = payroll.map(p => `
                <tr class="border-b">
                    <td class="p-4">${p.teacher_name}</td>
                    <td class="p-4">${p.total_hours}</td>
                    <td class="p-4">${p.total_amount.toLocaleString()}</td>
                </tr>
            `).join('');
            document.getElementById('payrollBody').innerHTML = html;
        }
        loadPayroll();
    </script>
</body>
</html>