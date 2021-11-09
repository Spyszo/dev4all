<?php
    function OpenCon() {
        $dbhost = $_ENV['DB_HOST'];
        $dbuser = $_ENV['DB_USER'];
        $dbpass = $_ENV['DB_PASSWORD'];
        $db = $_ENV['DB_NAME'];
        $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
        mysqli_set_charset($conn, 'utf8');
        
        return $conn;
    }
?>