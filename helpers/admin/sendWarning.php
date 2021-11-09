<?php 

session_start();
if ($_SESSION['admin'] == 1 && isset($_GET['id'], $_GET['reason'])) {
    $id = $_GET['id'];
    
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $msg_from = 0; //Administracja
    $msg_to = $_GET['id'];
    $msg_body = "Uwaga! Otrzymałeś ostrzeżenie! Powód:<br>" .$_GET['reason'] ."<br><br>Dalsze ostrzeżenia mogą skutkować blokadą konta.";
    $msg_created_at = time();
    $query = "INSERT INTO `messages` (`msg_id`, `msg_from`, `msg_to`, `msg_body`, `msg_created_at`) VALUES (NULL, '$msg_from', '$msg_to', '$msg_body', '$msg_created_at')";
    //echo "<br>" .$query ."<br>";
    $result = $conn->query($query);
    if ($result) {
        echo 'success';
    } else {
        echo $conn->error;
        echo "Wystąpił błąd podczas wysyłania wiadomości.";
    }
}

?>