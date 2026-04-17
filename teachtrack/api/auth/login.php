<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password required']);
    exit;
}

// Query users table
$users = supabase_query("users?email=eq.$email&select=id,password_hash,role,school_id,full_name,weekly_hour_cap");
if (empty($users)) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

$user = $users[0];
if (!password_verify($password, $user['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

// Start session
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['school_id'] = $user['school_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['weekly_hour_cap'] = $user['weekly_hour_cap'];

$redirect = match($user['role']) {
    'teacher' => '/teacher/dashboard.php',
    'principal' => '/principal/dashboard.php',
    'admin' => '/admin/dashboard.php'
};

echo json_encode(['success' => true, 'redirect' => $redirect]);
?>