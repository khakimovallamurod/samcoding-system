<?php 
    session_start();
    include_once '../../config.php';

    header('Content-Type: application/json; charset=utf-8');

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $status = intval($_POST['status']) ?? '';

    $ret = [];
    $db = new Database();

    $sql = $db->insert('contests', [
        'title' => $title,
        'description' => $description,
        'start_time'    => $start_time,
        'end_time'    => $end_time,
        'status'  => $status
    ]);
    if ($sql != 0) {
        $ret = ['success' => true, 'message' => 'Musobaqa qoshildi.'];
    } else {
        $ret = ['success' => false, 'message' => 'Musobaqa qoshilmadi.'];
    }

    echo json_encode($ret);
?>