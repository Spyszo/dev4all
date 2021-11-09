<?php
  session_start();
  if (isset($_POST['postID'], $_SESSION['userID'])) {

    $userID = $_SESSION['userID'];
    $postID = $_POST['postID'];

    if ($postID == 0) { 
      exit("Błędne ID posta");
    }

    include('../config/db_connection.php');
    $conn = OpenCon();

    $query = "INSERT INTO likes (`post_id`, `user_id`) VALUES ('$postID', '$userID')";
    $result = $conn->query($query);

    if (!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }

    echo "success";

  } else  {
    echo "Wystąpił błąd";
  }
?>