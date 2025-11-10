<?php
    session_start();
    include_once '../../config.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Tizimga kiring!']);
        exit;
    }

    $db = new Database();
    $contest_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($contest_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri ID!']);
        exit;
    }

    $sql = $db->delete('contests', "id = '$contest_id'");
    if ($sql) {
        echo json_encode(['success' => true, 'message' => 'Musobaqa o\'chirildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Musobaqa o\'chirishda xatolik!']);
    }

exit;
?>