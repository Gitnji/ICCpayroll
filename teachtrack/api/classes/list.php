<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';

header('Content-Type: application/json');

$school_id = $_SESSION['school_id'];
$classes = supabase_query("classes?school_id=eq.$school_id&select=*");
echo json_encode($classes);
?>