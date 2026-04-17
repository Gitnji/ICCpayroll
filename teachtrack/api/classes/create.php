<?php
session_start();
require_once '../../config/supabase.php';
require_once '../../includes/supabase-client.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/role-check.php';
checkRole('principal');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? '';
$rate = $data['hourly_rate'] ?? 500;
$school_id = $_SESSION['school_id'];

if (!$name || !in_array($rate, [500, 700])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$result = supabase_query('classes', 'POST', [
    'school_id' => $school_id,
    'name' => $name,
    'hourly_rate' => $rate
]);

echo json_encode(['success' => true]);
?>