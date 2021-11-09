<?php 

session_start();
if ($_SESSION['admin'] == 1 && isset($_GET['id'], $_GET['reason'])) {
    $id = $_GET['id'];
    
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $query = "SELECT * FROM posts WHERE post_id = $id";
    $result = $conn->query($query);
    if (!$result)
        exit("Nie znaleziono posta!");
    $post = $result->fetch_assoc();

    $query = "DELETE FROM posts WHERE post_id = {$_GET['id']}";
    $result = $conn->query($query);
    if (!$result)
        exit("Błąd podczas zapytania");

    $query = "SELECT * FROM posts WHERE post_id = $id";
    $result = $conn->query($query);
    if ($result->num_rows > 0)
        exit("Błąd podczas usuwania posta!");


    $msg_from = 0; //Administracja
    $msg_to = $post['user_id'];
    $msg_body = "Niestety... <br> Twój post pod tytułem ''{$post['title']}'' został odrzucony. Powód odrzucenia: <br><br> {$_GET['reason']}";
    $msg_created_at = time();
    $query = "INSERT INTO `messages` (`msg_id`, `msg_from`, `msg_to`, `msg_body`, `msg_created_at`) VALUES (NULL, '$msg_from', '$msg_to', '$msg_body', '$msg_created_at')";
    //echo "<br>" .$query ."<br>";
    $result = $conn->query($query);
    if ($result) {
        echo 'success';
    } else {
        echo "Wystąpił błąd podczas wysyłania wiadomości ale post został odrzucony.";
    }
}

?>