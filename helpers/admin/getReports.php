<?php 

session_start();

if ($_SESSION['admin'] == 1) {
    
    include('../../config/db_connection.php');
    $conn = OpenCon();


    $result = $conn->query("SELECT * FROM reports WHERE considered != 1");
    if (!$result) {
        exit("Błąd w zapytaniu (1)");
    }

    $postsNum = $result->num_rows;

    if (!isset($_GET['page'])) {
        echo $postsNum;
        exit();
    }

    if ($postsNum == 0) {
        echo "<h3>Brak zgłoszeń</h3>";
        exit();
    }

    $startFrom = $_GET['page'] * 5 - 5;
    $result = $conn->query("SELECT * FROM reports JOIN users ON reports.user_id = users.user_id WHERE considered = 0 LIMIT $startFrom, 5");
    if (!$result) 
        exit("Błąd w zapytaniu (2)" .$conn->error);
    
    while ($row = $result->fetch_assoc()) {
        $target = $row['target'];
        if ($row['target'] == 'user')
            $target = 'użytkownika';
        else if ($row['target'] == 'comment')
            $target = 'komentarz';
        $createdAt = date("d.m.Y", $row['created_at']);

        echo "
        <div class='report' id='{$row['report_id']}' onclick='getOneReport({$row['report_id']})'>
            <div class='date'>
                <span class='material-icons'>date_range</span>
                $createdAt
            </div>
            <div class='author'>
                <div class='avatar'>{$row['avatar_svg']}</div>
                <div class='name'>{$row['full_name']}</div>
            </div>
            zgłosił $target ({$row['target_id']})
            {$row['reason']}
            <span class='material-icons'>chevron_right</span>
        
        </div>";
    }
}

?>