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

    $query = "INSERT INTO bookmarks (`user_id`, `post_id`) VALUES ('$userID', '$postID')";
    $result = $conn->query($query);

    if (!$result) {
      exit("Wystąpił błąd");
    }
    
    echo "success";
    
  } else {
    echo "Wystąpił błąd";
  }
?>