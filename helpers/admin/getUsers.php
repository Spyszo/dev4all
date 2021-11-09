<?php 

session_start();

if ($_SESSION['admin'] == 1) {
    
    include('../../config/db_connection.php');
    $conn = OpenCon();

    $result = $conn->query("SELECT * FROM users WHERE users.user_id > 0 AND (users.admin is NULL OR users.admin = 0)");
    if (!$result) {
        echo "Błąd w zapytaniu(1)";
        exit();
    }
    
    $usersNum = $result->num_rows;

    if (!isset($_GET['page'])) {
        echo $usersNum;
        exit();
    }

    if ($usersNum == 0) {
        echo "<h3>Brak użytkowników</h3>";
        exit();
    }

    $startFrom = $_GET['page'] * 5 - 5;
    $result = $conn->query("SELECT * FROM users WHERE users.user_id > 0 AND (users.admin is NULL OR users.admin = 0) LIMIT $startFrom, 5");
    if (!$result) 
        exit("Błąd w zapytaniu (2)" .$conn->error);
    
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='user' id='{$row['user_id']}'>
            <div>
                <div class='avatar'>{$row['avatar_svg']}</div>
                <div class='name'>{$row['first_name']} {$row['last_name']}</div>
            </div>
            <div>
                <button id='banUser' onclick='banUserDialog({$row['user_id']})'>
                    <span class='material-icons'>"
                    .($row['banned'] == 1? "lock_open": "lock").
                    "</span>
                    <span class='text'>"
                        .($row['banned'] == 1? "Odblokuj": "Zablokuj").
                    "</span>
                </button>

                <button onclick='warningDialog({$row['user_id']})'>
                    <span class='material-icons'>warning</span>
                    Wyślij ostrzeżenie
                </button>

                <button id='resetPassword' onclick='resetPasswordDialog({$row['user_id']})'>
                    <span class='material-icons'>"
                        .($row['admin_pass_reset'] == 1? "done": "change_circle").
                    "</span>
                    Zresetuj hasło
                </button>
            </div>
            
        </div>";
    }
}

?>