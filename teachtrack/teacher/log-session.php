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
    <title>Log Session</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js');
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-6">Log a Teaching Session</h1>
    <form id="logForm" class="bg-white rounded-2xl shadow-md p-6">
        <div class="mb-4">
            <label class="block text-sm font-medium">Date</label>
            <input type="date" id="date" class="w-full p-2 border rounded-xl" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Class</label>
            <select id="class_id" class="w-full p-2 border rounded-xl" required>
                <option value="">Select Class</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Subject</label>
            <input type="text" id="subject" class="w-full p-2 border rounded-xl" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Hours Taught</label>
            <input type="number" id="hours" step="0.5" min="0.5" max="8" class="w-full p-2 border rounded-xl" required>
        </div>
        <p id="remainingHours" class="text-sm text-gray-600 mb-4">Loading remaining hours...</p>
        <button type="submit" id="submitBtn" class="w-full bg-blue-700 text-white rounded-xl px-6 py-3 hover:bg-blue-800">Log Session</button>
    </form>
    <script>
        let classes = [];
        let remaining = 0;

        async function loadClasses() {
            const res = await fetch('/api/classes/list.php');
            classes = await res.json();
            const select = document.getElementById('class_id');
            classes.forEach(c => {
                const option = document.createElement('option');
                option.value = c.id;
                option.textContent = `${c.name} (${c.hourly_rate} XAF/h)`;
                select.appendChild(option);
            });
        }

        async function updateRemaining() {
            const res = await fetch('/api/sessions/weekly-total.php');
            const data = await res.json();
            remaining = <?php echo $_SESSION['weekly_hour_cap']; ?> - data.total_hours;
            document.getElementById('remainingHours').textContent = `You have ${remaining} hours left this week.`;
        }

        document.getElementById('logForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const hours = parseFloat(document.getElementById('hours').value);
            if (hours > remaining) {
                alert('Not enough hours remaining this week.');
                return;
            }
            const data = {
                class_id: document.getElementById('class_id').value,
                subject: document.getElementById('subject').value,
                hours: hours,
                date: document.getElementById('date').value
            };
            const res = await fetch('/api/sessions/log.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                alert('Session logged successfully!');
                window.location.href = 'dashboard.php';
            } else {
                alert(result.message);
            }
        });

        loadClasses();
        updateRemaining();
    </script>
</body>
</html>