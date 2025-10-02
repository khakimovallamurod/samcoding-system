<?php

class Database{
    private $host = 'localhost';
    private $db_name = 'samcoding';
    private $username = 'root';
    private $password = '';
    private $link;
    function __construct() {
        $this->link = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->link) {
            exit("Bazaga ulanmadi!");
        }
    }
    public function query($query) {
        return mysqli_query($this->link, $query);
    }
    public function get_data_by_table($table, $arr, $con = 'no'){
        $sql = "SELECT * FROM ".$table. " WHERE ";
        $t = '';
        $i=0;
        $n = count($arr);
        foreach($arr as $key=>$val){
            $i++;
            if($i==$n){
                $t .= "$key = '$val'";
            }else{
                $t .= "$key = '$val' AND ";
            }
        }
        $sql .= $t;
        if ($con != 'no'){
            $sql .= $con;
        }
        $fetch = mysqli_fetch_assoc($this->query($sql));
        return $fetch;
    }
    public function get_data_by_table_all($table, $con = 'no'){
        $sql = "SELECT * FROM ".$table;
        if ($con != 'no'){
            $sql .= " ".$con;
        }
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function insert($table, $arr){
        $sql = "INSERT INTO ".$table. " ";
        $t1 = '';
        $t2 = '';
        $i = 0;
        $n = count($arr);
        foreach($arr as $key=>$val){
            $val = mysqli_real_escape_string($this->link, $val);
            $i++;
            if($i==$n){
                $t1 .= $key;
                $t2 .= "'".$val."'";
            }else{
                $t1 .= $key.', ';
                $t2 .= "'".$val."', ";
            }
        }
        $sql .= "($t1) VALUES ($t2);";
        $result = $this->query($sql);

        if ($result) {
            return mysqli_insert_id($this->link);
        } else {
            return 0;
        }
    }
    public function update($table, $arr, $con = 'no'){
            $sql = "UPDATE ".$table. " SET ";
            $t = '';
            $i=0;
            $n = count($arr);
            foreach($arr as $key=>$val){
                $i++;
                if($i==$n){
                    $t .= "$key = '$val'";
                }else{
                    $t .= "$key = '$val', ";
                }
            }
            $sql .= $t;
            if ($con != 'no'){
                $sql .= " WHERE ".$con;
            }
            return $this -> query($sql);
        }
    public function delete($table, $con = 'no'){
            $sql = "DELETE FROM ".$table;
            if ($con != 'no'){
                $sql .= " WHERE ".$con;
            }
            return $this -> query($sql);
        }
    public function get_problem_by_id($table, $id) {
        $sql = "SELECT 
            p.*, 
            u.fullname AS author_name,
            u.id AS user_id
        FROM {$table} p
        JOIN users u ON u.id = p.author_id
        WHERE p.id = " . intval($id);
        $fetch = mysqli_fetch_assoc($this->query($sql));
        return $fetch;
    }
    public function get_attempts_by_user($user_id){
       $sql = "SELECT 
            a.id AS attempt_id,
            a.problem_id,
            p.title AS problem_title,
            a.language,
            a.runTime,
            a.memory,
            a.status,
            a.tests_passed,
            a.created_at
        FROM attempts a
        JOIN problems p ON p.id = a.problem_id
        WHERE a.user_id = " . intval($user_id) . " 
        ORDER BY attempt_id DESC";
        $result = $this -> query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    
}
    
?>