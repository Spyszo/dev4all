<?php 
    if (isset($_POST['username'])) {
        if (isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            include('../config/db_connection.php');
            $conn = OpenCon();

            $query = "SELECT * FROM users  JOIN users_settings ON users_settings.user_id = users.user_id WHERE username = '$username'";
            $result = $conn->query($query);

            if (!$result)
                exit("Wystąpił błąd w zapytaniu");

            $row = $result->fetch_assoc();

            if (!password_verify($password, $row['password']))
                exit("Błędny login lub hasło");

            if ($row['banned'] == 1)
                exit ("banned");

            session_start();

            $_SESSION['username'] = $row['username'];
            $_SESSION['userID'] = $row['user_id'];
            $_SESSION['firstName'] = $row['first_name'];
            $_SESSION['lastName'] = $row['last_name'];
            $_SESSION['fullName'] = $row['full_name'];
            $_SESSION['admin'] = $row['admin'];
            $_SESSION['avatarSVG'] = $row['avatar_svg'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['avatarOptions'] = $row['avatar_options'];
            $_SESSION['darkMode'] = $row['dark_mode'];
            $_SESSION['confirmPostAccept'] = $row['confirm_post_accept'];

            echo "success";

        } else {
            echo "Podaj hasło";
        }
    } else {
        echo "Podaj login";
    }
?>