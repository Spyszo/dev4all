<?php
  session_start();
  if (isset($_SESSION['userID'], $_POST['postID'])) {
    $userID = $_SESSION['userID'];
    $postID = $_POST['postID'];

    if ($postID == 0) { 
      exit("Błędne ID posta");
    }

    include('../config/db_connection.php');
    $conn = OpenCon();
    $query = "DELETE FROM bookmarks WHERE bookmarks.post_id = '$postID' AND bookmarks.user_id = '$userID'";
    $result = $conn->query($query);

    if(!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }
    
    echo "success";

  } else {
    echo "Wystąpił błąd";
  }
?>