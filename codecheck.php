<?php
    include_once 'config.php';
    $db = new Database();
    $user_id  = $_POST['user_id'];
    $problem_id = $_POST['problem_id'];
    $tests = $db->get_data_by_table_all('tests', "WHERE problem_id = '$problem_id'");

    function run_code($language, $code, $stdin) {
        $url = "http://api.sampc.uz/api/v2/execute";

        $payload = [
            "language" => $language,
            "version" => "*",
            "files" => [["content" => $code]],
            "stdin" => $stdin
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp, true);
    }

    function check_problem($problem_id, $language, $code, $tests) {
        $test_count = 0;
        $last_runTime = 0;
        $last_memory = 0;
        $total_tests = count($tests);
        foreach ($tests as $test) {
            $test_input = $test['input'];
            $expected_output = $test['output'];

            $result = run_code($language, $code, $test_input);

            $actual_output = trim($result['run']['stdout'] ?? '');
            $stderr = $result['run']['stderr'] ?? '';
            $last_runTime = $result['runTime'] ?? 0;
            $last_memory = $result['run']['memoryUsage'] ?? 0;

            if ($stderr !== "") {
                return [
                    "tests_passed" => $test_count,
                    "total_tests" => $total_tests,
                    "status" => "Runtime Error",
                    "runTime" => $last_runTime,
                    "memory" => $last_memory
                ];
            }

            if ($actual_output === $expected_output) {
                $test_count++;
            } else {
                return [
                    "tests_passed" => $test_count + 1,
                    "total_tests" => $total_tests,
                    "status" => "Wrong Answer",
                    "runTime" => $last_runTime,
                    "memory" => $last_memory
                ];
            }
        }

        return [
            "tests_passed" => $test_count,
            "total_tests" => $total_tests,
            "status" => "Accept",
            "runTime" => $last_runTime,
            "memory" => $last_memory
        ];
    }
    
    
    $problem_id_str = str_pad($problem_id, 4, '0', STR_PAD_LEFT);
    $language = $_POST['language'];
    $code = $_POST['code'];
    $result = check_problem($problem_id_str, $language, $code, $tests);
    $arr = [
        "user_id"      => $user_id,       
        "problem_id"   => $problem_id,               
        "language"     => $language,             
        "code"         => $code,
        "status"       => $result['status'],           
        "tests_passed" => $result['tests_passed'],                  
        "total_tests"  => $result['total_tests'],                   
        "runTime"  => $result['runTime'],                   
        "memory"  => $result['memory']                
    ];
    $insert  = $db->insert("attempts", $arr);
    if ($insert!=0) {
        echo json_encode(['success' => true, 'message' => '✅ Dasturingiz yuborildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Yuborishda xatolik!']);
    }


