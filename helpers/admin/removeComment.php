<?php 

session_start();
if ($_SESSION['admin'] == 1 && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $query = "DELETE FROM comments WHERE comment_id = {$_GET['id']}";
    $result = $conn->query($query);

    if ($result) {
        echo 'success';
    } else {
        echo "Wystąpił błąd podczas usuwania komentarza";
    }
}

?>