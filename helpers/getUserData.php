<?php
  session_start();
  if ($_GET['userID']) {
    $userID = $_GET['userID'];

    if ($userID == 0) { 
      exit("Błędne ID użytkownika");
    }

    include('../config/db_connection.php');
    $conn = OpenCon();

    $query = "SELECT first_name, last_name, username, avatar_svg FROM users WHERE users.user_id = $userID";

    $result = $conn->query($query);
    if (!$result)
        exit("Błąd w zapytaniu");

    $user = $result->fetch_assoc();
    
    echo json_encode($user);
    
  } else {
    echo "Wystąpił błąd";
  }
?>