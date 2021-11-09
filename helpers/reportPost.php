<?php 

session_start();

if (isset($_SESSION['userID'], $_GET['id'], $_GET['reason'])) {
    include('../config/db_connection.php');
    $conn = OpenCon();

    $userID = $_SESSION['userID'];
    $postID = $_GET['id'];
    $reason = $_GET['reason'];
    $created_at = time();

    $result = $conn->query("INSERT INTO reports (`user_id`, `target`, `target_id`, `reason`, `created_at`) VALUE ('$userID', 'post', '$postID', '$reason', '$created_at')");
    if ($result) 
        echo 'success';
    else
        echo 'Błąd podczas wysyłania zgłoszenia...' .$conn->error;
} else {
    echo "Wystąpił błąd...";
}

?>