<?php
    session_start();
    include_once "../../config.php";
    $db = new Database();

    header('Content-Type: application/json');

    $test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($test_id == 0) {
        echo json_encode(['success' => false, 'message' => 'Test ID topilmadi']);
        exit;
    }

    $test = $db->get_data_by_table('contest_tests', ['id' => $test_id]);

    if (empty($test)) {
        echo json_encode(['success' => false, 'message' => 'Test topilmadi']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $test]);
?>
