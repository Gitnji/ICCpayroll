<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';

header('Content-Type: application/json');

$teacher_id = $_SESSION['user_id'];
if ($_SESSION['role'] === 'principal' && isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];
}

$week = date('W');
$year = date('Y');
$total = supabase_query("session_logs?teacher_id=eq.$teacher_id&week_number=eq.$week&year=eq.$year&select=hours_taught");
$sum = array_sum(array_column($total, 'hours_taught'));
echo json_encode(['total_hours' => $sum]);
?>