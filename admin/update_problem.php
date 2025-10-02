<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Tizimga kiring!']);
    exit;
}

$db = new Database();

$problem_id = $_POST['id'] ?? 0;
$title = $_POST['title'] ?? '';
$descript = $_POST['descript'] ?? '';
$input_format = $_POST['input_format'] ?? '';
$output_format = $_POST['output_format'] ?? '';
$constraints = $_POST['constraints'] ?? '';
$izoh = $_POST['izoh'] ?? '';
$difficulty = $_POST['difficulty'] ?? 0;
$category = $_POST['category'] ?? '';
$time_limit = $_POST['time_limit'] ?? 1000;
$memory_limit = $_POST['memory_limit'] ?? 16384;

if ($problem_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Masala ID topilmadi!']);
    exit;
}

$data = [
    'title' => $title,
    'descript' => $descript,
    'input_format' => $input_format,
    'output_format' => $output_format,
    'constraints' => $constraints,
    'izoh' => $izoh,
    'difficulty' => $difficulty,
    'category' => $category,
    'time_limit' => $time_limit,
    'memory_limit' => $memory_limit,
    'updated_at' => date('Y-m-d H:i:s')
];

$result = $db->update('problems', $data, "id = '$problem_id'");

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Masalani yangilashda xatolik!']);
    exit;
}

if (isset($_FILES['testcases_zip']) && $_FILES['testcases_zip']['error'] === UPLOAD_ERR_OK) {
    $zip = new ZipArchive;
    $zipFile = $_FILES['testcases_zip']['tmp_name'];

    if ($zip->open($zipFile) === TRUE) {
        $db->delete('tests', "problem_id = '$problem_id'");

        $inputs = [];
        $outputs = [];
        
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $fileInfo = pathinfo($filename);
            
            if (isset($fileInfo['extension']) && $fileInfo['extension'] === 'in') {
                $inputs[$fileInfo['filename']] = $zip->getFromIndex($i);
            } elseif (isset($fileInfo['extension']) && $fileInfo['extension'] === 'out') {
                $outputs[$fileInfo['filename']] = $zip->getFromIndex($i);
            }
        }
        $zip->close();

        $testCount = 0;
        foreach ($inputs as $testNum => $input) {
            if (isset($outputs[$testNum])) {
                $insertResult = $db->insert('tests', [
                    'problem_id' => $problem_id,
                    'input' => $input,
                    'output' => $outputs[$testNum]
                ]);
                
                if ($insertResult > 0) {
                    $testCount++;
                }
            }
        }

        if ($testCount > 0) {
            echo json_encode([
                'success' => true, 
                'message' => "Masala va {$testCount} ta test muvaffaqiyatli yangilandi!"
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Masala yangilandi, lekin testlar yuklanmadi!'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'ZIP faylni ochib bo\'lmadi!'
        ]);
    }
} else {
    echo json_encode([
        'success' => true, 
        'message' => 'Masala muvaffaqiyatli yangilandi!'
    ]);
}

?>