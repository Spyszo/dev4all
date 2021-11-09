<?php 
    if (isset(
    $_POST['firstName'], 
    $_POST['lastName'], 
    $_POST['username'], 
    $_POST['email'], 
    $_POST['password'], 
    $_POST['passwordCheck'], 
    $_POST['avatarSVG'], 
    $_POST['avatarOptions']
    )) { 
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $passwordCheck = $_POST['passwordCheck'];
        $avatarSVG = $_POST['avatarSVG'];
        $avatarOptions = $_POST['avatarOptions'];

        if ($password !== $passwordCheck) {
            echo "Hasła różnią się";
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        if (strlen($username) <= 3) {
            exit('Minimalna długość nazwy użytkownika to 3 znaki');
        }

        if (preg_match('/^[a-zA-Z0-9]+$/', $username) == 0) {
            exit('Nazwa użytkownika jest błędna!');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit('Podany adres e-mail jest błędny!');
        }

        include('../config/db_connection.php');
        $conn = OpenCon();

        $query = "SELECT * FROM users WHERE `email`='$email'";
        $result = $conn->query($query);
        if (!$result) {
            exit("Błąd... spróbuj później");
        } 
        if ($result->num_rows >= 1) {
            exit("Użytkownik o podanym adresie email już istnieje");
        }

        $fullName = $firstName ." " .$lastName;

        $createdAt = time();

        $query = "INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `full_name`, `avatar_svg`, `avatar_options`, `created_at`) VALUES ('$username', '$email', '$password', '$firstName', '$lastName', '$fullName', '$avatarSVG', '$avatarOptions', $createdAt)";
        
        $result = $conn->query($query);
        if(!$result) {
            exit("Błąd podczas rejestracji");
        }

        $newUserID = $conn->insert_id;
        $query = "INSERT into `users_settings` (`user_id`) VALUES ($newUserID)";
        $conn->query($query);

        echo "success";
    }
    else {
        echo "Podaj wszystkie dane";
    }
?>
