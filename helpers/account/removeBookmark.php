<?php
  session_start();
  if (isset($_SESSION['userID'], $_GET['id'])) {
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $query = "DELETE FROM bookmarks WHERE bookmarks.bookmark_id = {$_GET['id']}";
    $result = $conn->query($query);

    if(!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }
    
    echo "success";

  } else {
    echo "Wystąpił błąd";
  }
?>