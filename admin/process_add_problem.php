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
    $upload_method = $_POST['upload_method'] ?? 'zip'; // Qaysi usul tanlangani

    // Masalani bazaga qo'shish
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

    if ($problem_id != 0) {
        $testAddedCount = 0;

        // Qaysi usul tanlangani tekshirish
        if ($upload_method === 'zip') {
            // ZIP file orqali yuklash
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
                            $testAddedCount++;
                        }
                    }

                    if ($testAddedCount > 0) {
                        echo json_encode(['success' => true, 'message' => 'Masala va ' . $testAddedCount . ' ta test muvaffaqiyatli qo\'shildi!']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ZIP faylda test topilmadi!']);
                    }
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'ZIP faylni ochib bo\'lmadi!']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ZIP fayl yuklanmadi!']);
                exit;
            }
        } 
        elseif ($upload_method === 'manual') {
            // Qo'lda kiritish usuli
            $manual_inputs = $_POST['manual_input'] ?? [];
            $manual_outputs = $_POST['manual_output'] ?? [];

            // Kirish va chiqish massivlari mavjudligini tekshirish
            if (empty($manual_inputs) || empty($manual_outputs)) {
                echo json_encode(['success' => false, 'message' => 'Test case\'lar kiritilmagan!']);
                exit;
            }

            // Kirish va chiqish soni bir xil bo'lishi kerak
            if (count($manual_inputs) !== count($manual_outputs)) {
                echo json_encode(['success' => false, 'message' => 'Kirish va chiqish qiymatlari soni bir xil bo\'lishi kerak!']);
                exit;
            }

            // Test case'larni bazaga qo'shish
            foreach ($manual_inputs as $index => $input) {
                $input = trim($input);
                $output = trim($manual_outputs[$index] ?? '');

                // Bo'sh bo'lmagan testlarni qo'shish
                if (!empty($input) || !empty($output)) {
                    $db->insert('tests', [
                        'problem_id' => $problem_id,   
                        'input'      => $input,
                        'output'     => $output
                    ]);
                    $testAddedCount++;
                }
            }

            if ($testAddedCount > 0) {
                echo json_encode(['success' => true, 'message' => 'Masala va ' . $testAddedCount . ' ta test muvaffaqiyatli qo\'shildi!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Hech qanday test qo\'shilmadi! Barcha maydonlarni to\'ldiring.']);
            }
            exit;
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Noma\'lum yuklash usuli!']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Masalani qo\'shishda xatolik bor!']);
        exit;
    }
?>