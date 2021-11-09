<?php 

function generateRandomString($length) {
    $include_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charLength = strlen($include_chars);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $include_chars [rand(0, $charLength - 1)];
    }
    return $randomString;
}
 


session_start();
if ($_SESSION['admin'] == 1 && isset($_GET['id'])) {
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $newPassword = generateRandomString(20);

    $hashNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $result = $conn->query("UPDATE users SET `password` = '$hashNewPassword', `admin_pass_reset` = 1 WHERE users.user_id = {$_GET['id']}");
    if (!$result)
        exit("Wystąpił błąd w zapytaniu");

    $msg_from = 0; //Administracja
    $msg_to = $_GET['id'];
    $msg_body = "Twoje hasło zostało zresetowane przez Administrację. Twoje nowe hasło to: $newPassword";
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