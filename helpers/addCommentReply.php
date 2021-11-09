<?php
  session_start();
  if (isset($_POST['postID'], $_POST['body'], $_POST['commentID'], $_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $postID = $_POST['postID'];
    $parentID = $_POST['commentID'];
    $body = $_POST['body'];
    $publishedAt = time();

    if ($postID == 0) { 
      exit("Błędne ID posta");
    }

    if ($parentID == 0) { 
      exit("Błędne ID rodzica");
    }

    if (strlen($body) <= 1) {
      exit("Treść komentarza jest za krótka");
    }

    include('../config/db_connection.php');
    $conn = OpenCon();

    $query = "INSERT INTO comments (`post_id`, `user_id`, `parent_id`, `body`, `published_at`) VALUES ('$postID', '$userID', '$parentID', '$body', '$publishedAt')";
    $result = $conn->query($query);

    if (!$result) {
      exit("Wystąpił błąd w zapytaniu");
    }
    
    $commentID = $conn->insert_id;
    echo $commentID;
    
  } else {
    echo "Brak wszystkich danych";
  }
?>