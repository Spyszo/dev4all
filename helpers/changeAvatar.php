<?php
    session_start();
    if (isset($_POST['avatarSVG'], $_POST['avatarOptions'], $_SESSION['userID'])) {
        $avatarSVG = $_POST['avatarSVG'];
        $avatarOptions = $_POST['avatarOptions'];
        $userID = $_SESSION['userID'];

        if (strlen($avatarSVG) <= 3 && strlen($avatarOptions) <= 3) {
            exit("Błędne wartości");
        }
       
        include('../config/db_connection.php');
        $conn = OpenCon();

        $query = "UPDATE users SET `avatar_svg` = '$avatarSVG', `avatar_options` = '$avatarOptions' WHERE `user_id` = $userID";  
        $result = $conn->query($query); 
        
        if (!$result) {
            exit("Błąd w zapytaniu");
        } 
        
        echo "success";
        $_SESSION['avatarSVG'] = $avatarSVG;
        $_SESSION['avatarOptions'] = $avatarOptions;
        
    } else {
        echo "Nie podano wartości";
    }
?>