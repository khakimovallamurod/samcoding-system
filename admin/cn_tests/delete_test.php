<?php
    session_start();
    include_once "../../config.php";
    $db = new Database();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri so\'rov']);
        exit;
    }

    $test_id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;

    if ($test_id == 0) {
        echo json_encode(['success' => false, 'message' => 'Test ID topilmadi']);
        exit;
    }

    $deleted = $db->delete('contest_tests', "id = $test_id");

    if ($deleted) {
        echo json_encode(['success' => true, 'message' => 'Test case muvaffaqiyatli o\'chirildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Test case o\'chirishda xatolik!']);
    }
?>