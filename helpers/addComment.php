<?php
  session_start();
  if (isset($_SESSION['userID'], $_POST['postID'], $_POST['body'])) {

    $postID = $_POST['postID'];
    $body = $_POST['body'];
    $publishedAt = time();
    $userID = $_SESSION['userID'];

    if ($postID == 0) { 
      exit("Błędne ID posta");
    }

    if (strlen($body) <= 1) {
      exit("Treść komentarza jest za krótka");
    }

    include('../config/db_connection.php');
    $conn = OpenCon();

    $query = "INSERT INTO comments (`post_id`, `user_id`, `body`, `published_at`) VALUES ('$postID', '$userID', '$body', '$publishedAt')";
    $result = $conn->query($query);

    if(!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }

    $commentID = $conn->insert_id;
    echo $commentID;
  }
  else {
    echo "Wystąpił błąd";
  }
?>