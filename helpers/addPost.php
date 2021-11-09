<?php 
session_start();

if (isset($_FILES['post-thumbnail'], $_FILES['post-image'], $_POST['post-title'], $_POST['post-description'], $_POST['post-body'], $_SESSION['userID']) ) {
    include('../config/db_connection.php');
    $conn = OpenCon();
    
    $userID = $_SESSION['userID'];
    $title = $_POST['post-title'];
    $publishedAt = time();
    $description = $_POST['post-description'];
    $body = $_POST['post-body'];
    $body = $conn->real_escape_string($body);

    if (strlen($title) <= 6) {
        exit("Minimalna długość tytułu to 6 znaków");
    }

    if (strlen($body) <= 20) {
        exit("Minimalna długość  treści to 20 znaków");
    }

    if (strlen($description) <= 6) {
        exit("Minimalna długość opisu posta to 6 znaków");
    }

    $targetDirection = "upload/";

    //Upload zdjęcia głównego
    $file = $_FILES['post-image']['name'];
    $path = pathinfo($file);
    $ext = $path['extension'];
    $tempName = $_FILES['post-image']['tmp_name'];
    $newFileName = $targetDirection .$userID ."_" .time() .".".$ext;
    if (!move_uploaded_file($tempName, '../'.$newFileName)) {
        exit("Błąd podczas przenoszenia image");
    }
    $image = $newFileName;

    //Upload minuaturki
    $file = $_FILES['post-thumbnail']['name'];
    $path = pathinfo($file);
    $ext = $path['extension'];

    if ($ext == 'jpg' || $ext == 'png') {}
    else exit ("Złe rozszerzenie pliku");

    $tempName = $_FILES['post-thumbnail']['tmp_name'];
    $newFileName = $targetDirection .$userID ."_" .time() ."_thumbnail.".$ext;
    if (!move_uploaded_file($tempName, '../'.$newFileName)) {
        exit("Błąd podczas przenoszenia thumbnail");
    }
    $thumbnail = $newFileName;


    $query = "INSERT INTO posts (`post_id`, `user_id`, `title`, `image`, `thumbnail`, `description`, `body`, `published_at`, `accepted`) VALUES (NULL, '$userID', '$title', '$image', '$thumbnail', '$description', '$body', '$publishedAt', 0)";
    $result = $conn->query($query);

    if (!$result) {
        exit($conn->error ."Błąd podczas dodawania");
    }
    
    echo "success";
    
} else {
    echo "Błąd podczas dodawania... Brak wszystkich danych";
}

?>