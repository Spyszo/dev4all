<?php 
    session_start();
    if (isset($_POST['bookmarksPage'], $_SESSION['userID'])) {
        $page = $_POST['bookmarksPage'];
        $userID = $_SESSION['userID'];

        if ($page == 0) {
            $page = 1;
        }

        $resultsPerPage = 5;  

        include('../config/db_connection.php');
        $conn = OpenCon();

        $query = "SELECT * FROM bookmarks WHERE `user_id` = $userID";  
        $result = $conn->query($query);  

        if (!$result) {
            exit("Błąd w zapytaniu zliczającym liczbę zakładek");
        }

        $numberOfResults = $result->num_rows;  
        $numberOfPage = ceil ($numberOfResults / $resultsPerPage);
        $pageFirstResult = ($page - 1) * $resultsPerPage;  

        $query = "SELECT * FROM bookmarks JOIN posts ON posts.post_id = bookmarks.post_id WHERE bookmarks.user_id = $userID LIMIT $pageFirstResult, $resultsPerPage"; 
        
        $result = $conn->query($query);

        if (!$result) {
            exit("Błąd w zapytaniu zwracającym zakładki");
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $image = $row['image'];
                $title = $row['title'];
                $id = $row['bookmark_id'];
                echo "
                <div class='post'>
                    <div class='image'><img src='$image' /></div>
                    <div class='title'>$title</div>
                    <div class='delete'><span onclick='removeBookmark(this, $id)' class='material-icons'>bookmark_remove</span></div>
                </div>";
            }
        } else {
            echo "<p>To wszystko...</p>";
        }
    } else {
        echo "Błąd";
    }
?>