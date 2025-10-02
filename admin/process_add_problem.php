<?php
    session_start();
    include_once "../config.php";
    $db = new Database();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri so\'rov']);
        exit;
    }
    $title = trim($_POST['title']);
    $descript = trim($_POST['descript']);
    $input_format = trim($_POST['input_format'] ?? '');
    $output_format = trim($_POST['output_format'] ?? '');
    $constraints = trim($_POST['constraints'] ?? '');
    $difficulty = intval($_POST['difficulty']);
    $category = trim($_POST['category'] ?? '');
    $izoh = trim($_POST['izoh']);
    $time_limit = intval($_POST['time_limit']);
    $memory_limit = intval($_POST['memory_limit']);
    $author_id = intval($_POST['author_id']);

    $problem_id = $db->insert('problems', [
        'title'        => $title,
        'descript'     => $descript,
        'input_format' => $input_format,
        'output_format'=> $output_format,
        'constraints'  => $constraints,
        'difficulty'   => $difficulty,
        'category'     => $category,
        'izoh'         => $izoh,
        'time_limit'   => $time_limit,
        'memory_limit' => $memory_limit,
        'author_id'    => $author_id
    ]);
    if ($problem_id != 0){
        if (isset($_FILES['testcases_zip']) && $_FILES['testcases_zip']['error'] === UPLOAD_ERR_OK) {
            $zip = new ZipArchive;
            $zipFile = $_FILES['testcases_zip']['tmp_name'];

            if ($zip->open($zipFile) === TRUE) {
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
                foreach ($inputs as $testNum => $input) {
                    if (isset($outputs[$testNum])) {
                        $db->insert('tests', [
                            'problem_id' => $problem_id,   
                            'input'      => $input,
                            'output'     => $outputs[$testNum]
                        ]);
                    }
                }

                echo json_encode(['success' => true, 'message' => 'Masala muvaffaqiyatli qo\'shildi!']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'ZIP faylni ochib bo\'lmadi!']);
                exit;
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Masala qo\'shildi (testlarsiz)!']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Masalani qo\'shishda xatolik bor!']);
        exit;
    }

    
    
?>