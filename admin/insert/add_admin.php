<?php
session_start();
require_once '../../config.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$otm = trim($_POST['otm'] ?? '');
$course = trim($_POST['course'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($fullname === '' || $username === '' || $email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$db = new Database();
$existing = $db->get_data_by_table('users', ['username' => $username]);
if (!empty($existing)) {
    echo json_encode(['success' => false, 'message' => 'Username already exists.']);
    exit;
}

$hash = md5($password);
$insertId = $db->insert('users', [
    'fullname' => $fullname,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
    'otm' => $otm,
    'course' => $course,
    'password' => $hash,
    'role' => 'admin'
]);

if ($insertId > 0) {
    echo json_encode(['success' => true, 'message' => 'Admin account created successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create admin account.']);
}
