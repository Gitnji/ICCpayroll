<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/role-check.php';
checkRole('principal');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['full_name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$cap = $data['weekly_hour_cap'] ?? 8;
$school_id = $_SESSION['school_id'];

if (!$name || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$result = supabase_query('users', 'POST', [
    'school_id' => $school_id,
    'full_name' => $name,
    'email' => $email,
    'password_hash' => $hash,
    'role' => 'teacher',
    'weekly_hour_cap' => $cap
]);

echo json_encode(['success' => true]);
?>