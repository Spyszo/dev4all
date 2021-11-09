<?php 

if (!isset($_GET['searchString']))
    exit("Brak search stringa");

include('../config/db_connection.php');
$conn = OpenCon();

//Zamiana search stringa na tablice z pojedynczymi wyrazami
$searchString = $_GET['searchString'];
$wordsInSearchString = explode(" ", $searchString);

//Oblicza łączną ilości postów zawierających podany search string
$query = "SELECT * FROM users WHERE `user_id` > 0";

foreach($wordsInSearchString as $word) {
    $query .= " AND `full_name` LIKE '%$word%'";
}   
$result = $conn->query($query);  
if (!$result) 
    exit("Wystąpił błąd w zapytaniu zliczającym użytkowników");

$foundUsers = $result->num_rows;

//Wyświetla otrzymany wynik i kończy działanie PHP jeżeli wynik to 0
if ($foundUsers == 0) {
    exit("<h2 class='search-count'>Nie znaleziono użytkowników</h2>");
} else {
    echo "<h2 class='search-count'>Znalezieni użytkownicy ($foundUsers)</h2>";
}


$query = "SELECT * FROM users WHERE `user_id` > 0"; 

foreach($wordsInSearchString as $word) {
    $query = $query ." AND `full_name` LIKE '%$word%'";
} 
$query = $query ." LIMIT 5"; 
$result = $conn->query($query); 

if (!$result) {
    exit("Wystąpił błąd w zapytaniu zwracającym użytkowników");
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { 
        $avatarSVG = $row['avatar_svg'];
        $fullName = $row['full_name'];
        $userID = $row['user_id'];
        echo "
        <div class='user' id='$userID' onclick='goToUser($userID)'>
            $avatarSVG
            <div class='fullName'>$fullName</div>
        </div>";
    }

    if ($foundUsers - 5 > 0) {
        echo "<a class='showMore' href='/search?searchUsers=" .str_replace(' ', '+', $searchString) ."'><button>Pokaż więcej</button></a>";
    }
} else {
    echo "Brak wyników";
}



?>