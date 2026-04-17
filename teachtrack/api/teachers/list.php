<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/role-check.php';
checkRole('principal');

header('Content-Type: application/json');

$school_id = $_SESSION['school_id'];
$teachers = supabase_query("users?school_id=eq.$school_id&role=eq.teacher&select=id,full_name,email,weekly_hour_cap");
echo json_encode($teachers);
?>