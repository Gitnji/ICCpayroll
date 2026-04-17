<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/role-check.php';
checkRole('principal');

header('Content-Type: application/json');

$school_id = $_SESSION['school_id'];
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// Get all teachers
$teachers = supabase_query("users?school_id=eq.$school_id&role=eq.teacher&select=id,full_name");

// For each teacher, sum sessions
$payroll = [];
foreach ($teachers as $teacher) {
    $sessions = supabase_query("session_logs?teacher_id=eq.{$teacher['id']}&status=eq.normal&select=hours_taught,amount_xaf,class_id");
    $total_hours = array_sum(array_column($sessions, 'hours_taught'));
    $total_amount = array_sum(array_column($sessions, 'amount_xaf'));
    $payroll[] = [
        'teacher_name' => $teacher['full_name'],
        'total_hours' => $total_hours,
        'total_amount' => $total_amount
    ];
}

echo json_encode($payroll);
?>