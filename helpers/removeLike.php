<?php
  include('../config/db_connection.php');
  $conn = OpenCon();
  session_start();

  if (isset($_POST['postID'], $_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $postID = $_POST['postID'];

    if ($postID == 0) { 
      exit("Błędne ID posta");
    }

    $query = "DELETE FROM `likes` WHERE likes.post_id = '$postID' AND likes.user_id = '$userID'";
    $result = $conn->query($query);

    if(!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }
    
    echo "success";
    
  } else {
    echo "Wystąpił błąd";
  }
?>