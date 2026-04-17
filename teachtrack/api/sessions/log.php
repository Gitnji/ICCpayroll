<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$class_id = $data['class_id'] ?? '';
$subject = $data['subject'] ?? '';
$hours = $data['hours'] ?? 0;
$date = $data['date'] ?? date('Y-m-d');

$teacher_id = $_SESSION['user_id'];
$school_id = $_SESSION['school_id'];

if (!$class_id || !$subject || !$hours) {
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

// Get class rate
$classes = supabase_query("classes?id=eq.$class_id&select=hourly_rate");
if (empty($classes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid class']);
    exit;
}

$rate = $classes[0]['hourly_rate'];
$amount = $hours * $rate;

// Calculate week_number and year
$week = date('W', strtotime($date));
$year = date('Y', strtotime($date));

// Sum existing hours this week
$existing = supabase_query("session_logs?teacher_id=eq.$teacher_id&week_number=eq.$week&year=eq.$year&select=hours_taught");
$total_existing = array_sum(array_column($existing, 'hours_taught'));
$cap = $_SESSION['weekly_hour_cap'];

if ($total_existing + $hours > $cap) {
    // Log blocked
    supabase_query('session_logs', 'POST', [
        'teacher_id' => $teacher_id,
        'class_id' => $class_id,
        'subject' => $subject,
        'hours_taught' => $hours,
        'session_date' => $date,
        'week_number' => $week,
        'year' => $year,
        'amount_xaf' => $amount,
        'status' => 'blocked'
    ]);
    $remaining = $cap - $total_existing;
    echo json_encode(['success' => false, 'message' => "Weekly cap exceeded. You have $remaining hours remaining this week."]);
    exit;
}

// Insert normal
$result = supabase_query('session_logs', 'POST', [
    'teacher_id' => $teacher_id,
    'class_id' => $class_id,
    'subject' => $subject,
    'hours_taught' => $hours,
    'session_date' => $date,
    'week_number' => $week,
    'year' => $year,
    'amount_xaf' => $amount,
    'status' => 'normal'
]);

echo json_encode(['success' => true]);
?>