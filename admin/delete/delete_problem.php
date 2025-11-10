<?php
    session_start();
    include_once '../../config.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Tizimga kiring!']);
        exit;
    }

    $db = new Database();
    $problem_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($problem_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri masala ID!']);
        exit;
    }

    try {
        $db->delete('tests', "problem_id = '$problem_id'");
        
        $result = $db->delete('problems', "id = '$problem_id'");

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Masala muvaffaqiyatli o\'chirildi!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Masalani o\'chirishda xatolik!']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Xatolik: ' . $e->getMessage()]);
    }

exit;
?>