<?php 
    session_start();
    include_once '../config.php';

    header('Content-Type: application/json; charset=utf-8');

    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $email    = $_POST['email'] ?? '';
    $address  = $_POST['address'] ?? '';
    $role     = $_POST['role'] ?? '';
    $password = !empty($_POST['password']) ? md5($_POST['password']) : '';

    $ret = [];

    $db = new Database();

    $checkUser = $db->get_data_by_table('users', ['username' => $username]);
    if (!empty($checkUser)) {
        echo json_encode(['error' => 1, 'message' => 'This username is already taken']);
        exit;
    }
    $sql = $db->insert('users', [
        'fullname' => $fullname,
        'username' => $username,
        'phone'    => $phone,
        'email'    => $email,
        'address'  => $address,
        'role'     => $role,
        'password' => $password
    ]);
    if ($sql) {
        $user = $db->get_data_by_table('users', ['username' => $username]);
        if ($user && isset($user['id'])) {
            $_SESSION['id'] = $user['id'];
        }
        $ret = ['error' => 0, 'message' => 'Successfully registered!'];
    } else {
        $ret = ['error' => 1, 'message' => 'Error! Please try again.'];
    }

    echo json_encode($ret);
?>