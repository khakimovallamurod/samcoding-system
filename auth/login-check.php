<?php
session_start();
include_once '../config.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$ret = [];

// --- Static admin credentials (hardcoded fallback) ---
if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['id']       = 0;
    $_SESSION['username'] = 'admin';
    $_SESSION['fullname'] = 'Administrator';
    $_SESSION['role']     = 'admin';
    $ret = ['error' => 0, 'message' => 'Welcome, Administrator!', 'redirect' => 'admin'];
    echo json_encode($ret);
    exit;
}

// --- Database users ---
$db     = new Database();
$hashed = md5($password);
$fetch  = $db->get_data_by_table('users', ['username' => $username, 'password' => $hashed]);

if ($fetch) {
    $_SESSION['id']       = $fetch['id'];
    $_SESSION['username'] = $fetch['username'];
    $_SESSION['fullname'] = $fetch['fullname'];
    $_SESSION['role']     = $fetch['role'];

    $redirect = ($fetch['role'] === 'admin') ? 'admin' : 'user';
    $ret = ['error' => 0, 'message' => 'You have successfully logged in!', 'redirect' => $redirect];
} else {
    $ret = ['error' => 1, 'message' => 'Invalid username or password.'];
}

echo json_encode($ret);
