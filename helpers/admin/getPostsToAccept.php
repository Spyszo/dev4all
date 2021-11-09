<?php 

function getPostDate($postDate){
    $currentTime = getDate();
    $diff =  $currentTime[0] - $postDate;

    if ($diff > 43200 && getDate($postDate)["mday"] + getDate($postDate)["mon"] + getDate($postDate)["year"] + 1 !== $currentTime["mday"] + $currentTime["mon"] + $currentTime["year"]) {
        return date('j.m.Y h:i', $postDate);
    }

    elseif ($diff > 43200 && getDate($postDate)["mday"] === $currentTime["mday"]) return "Dodano dzisiaj o godzinie  " .date('G:i', $postDate);
    elseif ($diff > 43200 && getDate($postDate)["mday"] === $currentTime["mday"] - 1) return "Dodano wczoraj o godzinie  " .date('G:i', $postDate);


    elseif ($diff > 3600) {
        if ($diff <= 4400) return round($diff/3600) ." godzinę temu";
        elseif ($diff <= 16200) return round($diff/3600) ." godziny temu";
        else return round($diff/3600) ." godzin temu";
    }

    elseif ($diff > 60) {
        if ($diff <= 90) return round($diff/60) ." minutę temu";
        elseif ($diff <= 270) return round($diff/60) ." minuty temu";
        else return round($diff/60) ." minut temu";
    }

    elseif ($diff > 0) {
        if ($diff == 1) return $diff ." sekundę temu";
        elseif ($diff <= 4) return $diff ." sekundy temu";
        else return $diff ." sekund temu";
    } 
}

session_start();
if ($_SESSION['admin'] = 1) {
    include('../../config/db_connection.php');
    $conn = OpenCon();

    if (!isset($_GET['page'])) {
        $result = $conn->query("SELECT * FROM posts WHERE accepted = 0");
        if ($result) {
            $postsNum = $result->num_rows;
            echo $postsNum;
            exit();
        } else {
            echo 0;
            exit();
        }
    }

    $startFrom = $_GET['page'] * 4 - 4; 

    $query = "SELECT * FROM posts JOIN users ON users.user_id = posts.user_id WHERE accepted = 0 ORDER BY posts.post_id DESC LIMIT $startFrom, 4";

    $result = $conn->query($query);
    if ($result->num_rows == null || $result->num_rows == 0) {
        exit("<h3>Brak postów do akceptacji</h3>");
    }

    echo "<div class='container'>";

    while ($row = $result->fetch_assoc()){
        $publishedAt = getPostDate($row['published_at']);

        echo "
            <div class='post-container'>
                <div class='post'> 
                    <div class='left-side'>
                        <div class='thumbnail'><img src='{$row['thumbnail']}' alt='miniaturka'/></div>
                        <div>
                            <div class='title'>{$row['title']}</div>
                            <div class='description'>{$row['description']}</div>
                        </div>
                    </div>
                    <div class='right-side'>
                        <div class='author' onclick='goToUser({$row['user_id']})'>
                            <div class='avatar'>{$row['avatar_svg']}</div>
                            <div class='name'>{$row['full_name']}</div>
                        </div>
                        <div class='date'>
                            <span class='material-icons'>date_range</span>
                            $publishedAt
                        </div>
                    </div>
                </div>
                <div class='buttons'>
                    <div class='left-side'>
                        <button onclick='goToPost({$row['post_id']})'>
                            <span class='material-icons'>preview</span>
                            Wyświetl post
                        </button>
                    </div>
                    <div class='right-side'>
                        <button onclick='discardPostDialog({$row['post_id']})'>
                            <span class='material-icons'>delete</span>
                            Odrzuć
                        </button>
                        <button onclick='acceptPostDialog({$row['post_id']})'>
                            <span class='material-icons'>done</span>
                            Akceptuj
                        </button>
                    </div>
                </div>
            </div>
        ";
    }
    echo "</div>";

} else {
    echo "Brak dostępu";
}

?>