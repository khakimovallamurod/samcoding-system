<?php
    include_once 'config.php';
    $db = new Database();
    $problem = $db->get_data_by_table('problems', ['id'=>   1]);
    var_dump($problem);



?>