<?php 

session_start();
if (isset($_SESSION['admin'], $_GET['id']) && $_SESSION['admin'] == 1) {
    include('../../config/db_connection.php');
    $conn = OpenCon();
    
    if ($_GET['id'] == 0) {
        $query = "SELECT * FROM reports JOIN users ON reports.user_id = users.user_id LIMIT 1";
    } else {
        $query = "SELECT * FROM reports JOIN users ON reports.user_id = users.user_id WHERE report_id = {$_GET['id']}";
    }

    $result = $conn->query($query);
    if (!$result)
        exit("Błąd w zapytaniu");

    $report = $result->fetch_assoc();

    $createdAt = date("d.m.Y H:i", $report['created_at']);

    echo "
        <div class='header'>
            <div> Zgłoszenie od: <div class='author'>{$report['avatar_svg']} {$report['full_name']}</div> </div>
            <div class='date'>
                <span class='material-icons'>date_range</span>
                $createdAt
            </div> 
        </div>
    ";

    if ($report['target'] == 'post') {
        $result = $conn->query("SELECT post_id, title, full_name FROM posts JOIN users ON users.user_id = posts.user_id WHERE posts.post_id = {$report['target_id']}");
        if (!$result)
            exit("Wystąpił błąd...");
        $post = $result->fetch_assoc();
        if (!$post) {
            echo "<div class='body'>Post został usunięty.</div>";
            echo "<div class='buttons'><button>Odrzuć zgłoszenie</button></div>";
            exit();
        }

        echo "
        <div class='body'>
            {$report['first_name']} zgłosił post ''{$post['title']}'' użytkownika {$post['full_name']}. <br><br>
            Powód zgłoszenia: <br>''{$report['reason']}''
        </div>";

        echo "
        <div class='buttons'>
            <button disabled>Pokaż<br>post</button>
            <button>Usuń<br>post</button>
            <button>Pokaż<br>autora</button>
            <button>Zablokuj<br>autora</button>
            <button>Wyślij<br>ostrzeżenie</button>
            <button>Odrzuć<br>zgłoszenie</button>
            <button>Oznacz jako rozpatrzone</button>
        </div>
        ";
    }

    if ($report['target'] == 'user') {
        $result = $conn->query("SELECT full_name FROM users WHERE users.user_id = {$report['target_id']}");
        if (!$result)
            exit("Wystąpił błąd...");
        $user = $result->fetch_assoc();
        if (!$user) {
            echo "<div class='body'>Użytkownik został usunięty.</div>";
            echo "<div class='buttons'><button>Odrzuć zgłoszenie</button></div>";
            exit();
        }

        echo "
        <div class='body'>
            {$report['first_name']} zgłosił użytkownika ''{$user['full_name']}''. <br><br>
            Powód zgłoszenia: <br>''{$report['reason']}''
        </div>";

        echo "
        <div class='buttons'>
            <button>Pokaż<br>użytkownika</button>
            <button>Zablokuj<br>użytkownika</button>
            <button>Wyślij<br>ostrzeżenie</button>
            <button>Odrzuć<br>zgłoszenie</button>
            <button>Oznacz jako rozpatrzone</button>
        </div>
        ";
    }
} 

?>