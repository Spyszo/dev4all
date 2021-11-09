<?php 

session_start();

if (isset($_SESSION['userID'], $_POST['msgTo'], $_POST['msgBody'])) { 
    include('../../config/db_connection.php');
    $conn = OpenCon();
    $createdAt = time();

    $result = $conn->query("INSERT INTO messages (msg_id, msg_from, msg_to, msg_body, msg_created_at) VALUES (NULL, {$_SESSION['userID']}, {$_POST['msgTo']}, '{$_POST['msgBody']}', $createdAt)");
    if ($result) 
        echo "success";
    else
        echo "Błąd w zapytaniu";
} else {
    echo "Wystąpił błąd...";
}

?>