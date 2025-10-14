<?php
    session_start();
    include_once "../../config.php";
    $db = new Database();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri so\'rov']);
        exit;
    }

    $problem_id = isset($_POST['problem_id']) ? intval($_POST['problem_id']) : 0;
    $input = trim($_POST['input'] ?? '');
    $output = trim($_POST['output'] ?? '');

    if ($problem_id == 0) {
        echo json_encode(['success' => false, 'message' => 'Problem ID topilmadi']);
        exit;
    }

    if (empty($input) || empty($output)) {
        echo json_encode(['success' => false, 'message' => 'Input va Output bo\'sh bo\'lmasligi kerak']);
        exit;
    }

    $test_id = $db->insert('tests', [
        'problem_id' => $problem_id,
        'input' => $input,
        'output' => $output
    ]);

    if ($test_id != 0) {
        echo json_encode(['success' => true, 'message' => 'Test case muvaffaqiyatli qo\'shildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Test case qo\'shishda xatolik!']);
    }
?>