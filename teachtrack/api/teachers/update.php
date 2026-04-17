<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/role-check.php';
checkRole('principal');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$cap = $data['weekly_hour_cap'] ?? '';

if (!$id || $cap === '') {
    echo json_encode(['success' => false, 'message' => 'ID and cap required']);
    exit;
}

$result = supabase_query("users?id=eq.$id", 'PATCH', ['weekly_hour_cap' => $cap]);
echo json_encode(['success' => true]);
?>