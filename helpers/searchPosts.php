<?php 

if (!isset($_GET['searchString']))
    exit("Brak search stringa");

include('../config/db_connection.php');
$conn = OpenCon();

//Zamiana search stringa na tablice z pojedynczymi wyrazami
$searchString = $_GET['searchString'];
$wordsInSearchString = explode(" ", $searchString);

//Oblicza łączną ilości postów zawierających podany search string
$query = "SELECT * FROM posts WHERE";
$i = 0;
foreach($wordsInSearchString as $word) {
    if ($i >= 1) $query .= " AND `title` LIKE '%$word%'";
    else         $query .= " `title` LIKE '%$word%'";
    $i ++;
}   
$result = $conn->query($query);  
if (!$result) 
    exit("Wystąpił błąd w zapytaniu zliczającym posty");

$foundPosts = $result->num_rows;

//Wyświetla otrzymany wynik i kończy działanie PHP jeżeli wynik to 0
if ($foundPosts == 0) {
    exit("<h2 class='search-count'>Nie znaleziono postów</h2>");
} else {
    echo "<h2 class='search-count'>Znalezione posty ($foundPosts)</h2>";
}


$query = "SELECT * FROM posts WHERE"; 
$i = 0;
foreach($wordsInSearchString as $word) {
    if ($i >= 1) {
        $query = $query ." AND `title` LIKE '%$word%'";
    } else {
        $query = $query ." `title` LIKE '%$word%'";
    }
    $i ++;
} 
$query = $query ." LIMIT 7"; 
$result = $conn->query($query); 

if (!$result) {
    exit("Wystąpił błąd w zapytaniu zwracającym posty");
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { 
        $image = $row['image'];
        $title = $row['title'];
        $postID = $row['post_id'];
        $publishedAt = date('d-m-Y' ,$row['published_at']);
        echo "
        <div class='post' id='$postID' onclick='goToPost($postID)'>
            <div class='image'><img src='$image' /></div>
            <div class='title'>$title</div>
            <div class='date'>$publishedAt</div>
        </div>";
    }

    if ($foundPosts - 7 > 0) {
        echo "<a class='showMore' href='/search?searchString=" .str_replace(' ', '+', $searchString) ."'><button>Pokaż więcej</button></a>";
    }
} else {
    echo "Brak wyników";
}



?>