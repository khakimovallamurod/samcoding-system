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
    $input = trim($_POST['input'] ?? '');
    $output = trim($_POST['output'] ?? '');
    if ($test_id == 0) {
        echo json_encode(['success' => false, 'message' => 'Test ID topilmadi']);
        exit;
    }

    if (empty($input) || empty($output)) {
        echo json_encode(['success' => false, 'message' => 'Input va Output bo\'sh bo\'lmasligi kerak']);
        exit;
    }

    $updated = $db->update('contest_tests', [
        'input' => $input,
        'output' => $output
    ], " id=$test_id");

    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Test case muvaffaqiyatli yangilandi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Test case yangilashda xatolik!']);
    }
?>