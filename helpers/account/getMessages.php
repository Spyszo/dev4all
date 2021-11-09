<?php 

session_start();

if (isset($_SESSION['userID'], $_GET['fromID'])) {
        include('../../config/db_connection.php');
        $conn = OpenCon();

        $query = "SELECT * FROM messages WHERE (`msg_to` = {$_SESSION['userID']} AND `msg_from` = {$_GET['fromID']}) OR (`msg_to` = {$_GET['fromID']} AND `msg_from` = {$_SESSION['userID']})";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $date = getPostDate($row['msg_created_at']);
                echo "
                    <div class='message " .($row['msg_from'] == $_SESSION['userID']? "message-outgoing": "message-incoming") ."'>
                        <div class='body'>{$row['msg_body']}</div>
                        <div class='date'>$date</div>
                    </div>            
                ";
            } 

            echo "<script>
                $('div.messages').scrollTop( $('div.messages')[0].scrollHeight );
                $('div.messages').focus();
            </script>";
        } else {
            echo "<h2>Brak wiadomości</h2>";
        }
    } 


    function getPostDate($postDate){
        $currentTime = getDate();
        $diff =  $currentTime[0] - $postDate;
    
        if ($diff > 43200 && getDate($postDate)["mday"] + getDate($postDate)["mon"] + getDate($postDate)["year"] + 1 !== $currentTime["mday"] + $currentTime["mon"] + $currentTime["year"]) {
            return date('j.m.Y h:i', $postDate);
        }
    
        elseif ($diff > 43200 && getDate($postDate)["mday"] === $currentTime["mday"]) return "dzisiaj o godzinie  " .date('G:i', $postDate);
        elseif ($diff > 43200 && getDate($postDate)["mday"] === $currentTime["mday"] - 1) return "wczoraj o godzinie  " .date('G:i', $postDate);
    
    
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
?>