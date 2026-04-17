<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';

header('Content-Type: application/json');

$teacher_id = $_SESSION['user_id'];
$sessions = supabase_query("session_logs?teacher_id=eq.$teacher_id&order=logged_at.desc&select=*");
echo json_encode($sessions);
?>