<?php 

session_start();
if (isset($_SESSION['userID'])) {
    if (isset($_GET['confirmPostAccept'])) {     
        include('../config/db_connection.php');
        $conn = OpenCon();
        $query = "UPDATE users_settings SET confirm_post_accept = {$_GET['confirmPostAccept']} WHERE `user_id` = {$_SESSION['userID']}";
        $result = $conn->query($query);
        if ($result) {
            $_SESSION['confirmPostAccept'] = 1;
        }
    }
}

?>