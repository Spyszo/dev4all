<?php 

session_start();
if (isset($_SESSION['userID'], $_GET['id']) && $_SESSION['admin'] == 1) {
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $result = $conn->query("UPDATE users SET banned = 0 WHERE users.user_id = {$_GET['id']}");
    if (!$result)
        exit("Błąd w zapytaniu");
    
    echo "success";
}

?>