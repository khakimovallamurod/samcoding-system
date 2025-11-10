<?php
    header('Content-Type: application/json');
    include_once '../../config.php';
    $db = new Database();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $status = $_POST['status'] ?? '';

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID topilmadi!']);
            exit;
        }
        $sql = $db->update('contests', [
            'title' => $title,
            'description' => $description,
            'start_time'    => $start_time,
            'end_time'    => $end_time,
            'status'  => $status,
        ], " id = $id");
        if ($sql != 0) {
            $ret = ['success' => true, 'message' => 'Musobaqa yangilandi.'];
        } else {
            $ret = ['success' => false, 'message' => 'Musobaqa yangilanmadi.'];
        }

        echo json_encode($ret);
        
    }else{
        $ret = ['success' => false, 'message' => 'Method not found!'];
        echo json_encode($ret);
    }
?>
