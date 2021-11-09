<?php 

session_start();
if ($_SESSION['admin'] == 1 && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $query = "UPDATE posts SET accepted = 1 WHERE post_id = $id";
    $result = $conn->query($query);
    if (!$result)
        exit("Błąd podczas zapytania");

    $query = "SELECT * FROM posts WHERE post_id = $id";
    $result = $conn->query($query);
    if (!$result)
        echo "Nie znaleziono posta ale zaakceptowano";
    $post = $result->fetch_assoc();


    $msg_from = 0; //Administracja
    $msg_to = $post['user_id'];
    $msg_body = "Gratulacje! Twój post został zaakceptowany. Od teraz jest on publiczny. Znajdziesz go pod adresem <a href=http://localhost:3000/post?id=$id target=_blank>http://localhost:3000/post?id=$id</a>";
    $msg_created_at = time();
    $query = "INSERT INTO `messages` (`msg_id`, `msg_from`, `msg_to`, `msg_body`, `msg_created_at`) VALUES (NULL, '$msg_from', '$msg_to', '$msg_body', '$msg_created_at')";
    //echo "<br>" .$query ."<br>";
    $result = $conn->query($query);
    if ($result) {
        echo 'success';
    } else {
        echo $conn->error;
        echo "Wystąpił błąd podczas wysyłania wiadomości ale post został zaakceptowany.";
    }
}

?>