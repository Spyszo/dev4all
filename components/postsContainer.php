
<?php 

    // Domyślne wartości //
    $page = 1;
    $limit = 12;
    $firstExtended = true;
    ////////////////////////


    $searchStringCondition = 1;
    $userIDCondition = 1;
    $tagCondition = 1;
    $order = "DESC";
    $sort = "post_id";


    if (isset($_GET['page']))
        $page = intval($_GET['page']);  

    if (isset($_GET['limit']))
        $limit = intval($_GET['limit']);  

    if (isset($_GET['tag'])) 
        $tagCondition = "`tags` LIKE '%{$_GET['tag']}%'";

    if (isset($_GET['order']))
        $order = $_GET['order'];

    if (isset($_GET['sort']))
        $sort = $_GET['sort'];

    if (isset($_GET['userID']))
        $userIDCondition = "posts.user_id = {$_GET['userID']}";  
    
    if (isset($_GET['searchString'])) {
        $wordsInSearchString = explode(" ", $_GET['searchString']);
        $i = 0;
        foreach ($wordsInSearchString as $word) {
            if ($i == 0) $searchStringCondition = " `title` LIKE '%$word%'";
            else         $searchStringCondition .= " AND `title` LIKE '%$word%'";
            $i++;
        }
    }
        
    if (isset($_GET['searchString']) || isset($_GET['userID']) || isset($_GET['tag']) || $page > 1) {
        $firstExtended = false;
    } else {
        $limit ++;
    }

    $startFrom = $page * $limit - $limit;
    if ((isset($_GET['searchString']) || isset($_GET['userID']) || isset($_GET['tag'])) && $firstExtended == true && $page > 1)
        $startFrom ++;

    if (!isset($_SESSION['userID'])) {
        $query = "SELECT posts.post_id, posts.user_id, posts.title, posts.description, posts.published_at, posts.thumbnail, users.username, users.avatar_svg, (SELECT Count(post_id) FROM likes WHERE likes.post_id = posts.post_id) AS likes_count FROM posts JOIN users on posts.user_id = users.user_id WHERE $userIDCondition AND $searchStringCondition AND $tagCondition AND posts.accepted = 1 ORDER BY $sort $order";
    } else {
        $query = "SELECT posts.post_id, posts.user_id, posts.title, posts.description, posts.published_at, posts.thumbnail, users.username, users.avatar_svg, (SELECT 1 FROM likes where likes.user_id = {$_SESSION['userID']} and likes.post_id = posts.post_id) as liked, (SELECT 1 FROM bookmarks where bookmarks.user_id = {$_SESSION['userID']} and bookmarks.post_id = posts.post_id) as bookmarked, (SELECT Count(post_id) FROM likes WHERE likes.post_id = posts.post_id) AS likes_count FROM posts JOIN users on posts.user_id = users.user_id WHERE $userIDCondition AND $searchStringCondition AND $tagCondition AND posts.accepted = 1 ORDER BY $sort $order";
    }
?>

<div class="postsContainer" id="postsContainer">
    <div class="view-options">
        <?php if (isset($_GET['searchString']) || isset($_GET['tag']) || isset($_GET['userID'])){?>
        <div class="current-options">
            <?php if (isset($_GET['searchString'])) echo "<div class='option searchString'><span>''</span>{$_GET['searchString']}<span>''</span><span class='material-icons'>delete</span></div>"?>
            <?php if (isset($_GET['tag'])) echo "
                <div class='option tag'>
                    <span>#</span>{$_GET['tag']}
                    <span class='material-icons'>delete</span>
                </div>"?>
            <?php if (isset($_GET['userID'])) {
                $result = $conn->query("SELECT avatar_svg, first_name  FROM users WHERE `user_id` = {$_GET['userID']}");
                if ($result) {
                    $user = $result->fetch_assoc();
                    echo "<div class='option user'>{$user['avatar_svg']} {$user['first_name']} <span class='material-icons'>delete</span></div>";
                }
            }?>
        </div>
        <?php } else { ?>
            <div class='empty-space'></div>
        <?php } ?>
        <div class="current-page">
            <?php 
                if ($page > 1) {
                    $queryURL = $_GET;
                    unset($queryURL['page']);
                    $queryURL_result = http_build_query($queryURL);
                    $previousPage = $page - 1;
                    echo "<a href='/?page=$previousPage&$queryURL_result' class='previous'><span class='material-icons'>navigate_before</span></a>";
                } else {
                    echo "<div class='empty-space'></div>";
                }


                echo "<p>$page strona</p>";

                $result = $conn->query("SELECT count(post_id) as numberOfPosts FROM posts JOIN users on posts.user_id = users.user_id WHERE $userIDCondition AND $searchStringCondition AND $tagCondition AND posts.accepted = 1");
                if($result)
                    $numberOfPosts = $result->fetch_assoc()['numberOfPosts'];
                if (isset($_GET['searchString']) || isset($_GET['userID']) || isset($_GET['tag']) ||  $page > 1) {
                    $numberOfPages = ceil(($numberOfPosts - 1) / $limit);
                } else {
                    $numberOfPages = ceil($numberOfPosts / $limit);
                }
                $queryURL = $_GET;
                unset($queryURL['page']);
                $queryURL_result = http_build_query($queryURL);
                $nextPage = $page + 1;

                if ($page != $numberOfPages) {
                    echo "<a href='/?page=$nextPage&$queryURL_result' class='next'><span class='material-icons'>navigate_next</span></a>";
                } else {
                    echo "<div class='empty-space'></div>";
                }
            ?>
        </div>
        <div class="filters">
            <select id="postsLimit">
                <option <?php if (isset($_GET['limit']) && $_GET['limit'] == 12) echo "selected"?> value="12">12</option>
                <option <?php if (isset($_GET['limit']) && $_GET['limit'] == 24) echo "selected"?> value="24">24</option>
                <option <?php if (isset($_GET['limit']) && $_GET['limit'] == 36) echo "selected"?> value="36">36</option>
                <option <?php if (isset($_GET['limit']) && $_GET['limit'] == 48) echo "selected"?> value="48">48</option>
            </select>
            <select name="sortBy" id="sortBy">
                <option value="post_id" <?php if(isset($_GET['sort']) && $_GET['sort'] == "post_id") echo "selected"?>>Data dodania</option>
                <option value="likes_count" <?php if(isset($_GET['sort']) && $_GET['sort'] == "likes_count") echo "selected"?>>Popularność</option>
            </select>
            <?php 
                if (isset($_GET['order']) && $_GET['order'] == 'ASC') {
                    echo "<div class='sort-direction ASC'>Rosnąco<span class='material-icons'>expand_less</span></div>";
                }else {
                    echo "<div class='sort-direction'>Malejąco<span class='material-icons'>expand_more</span></div>";
                }
            ?>
        </div>
    </div>

    <?php
        $result = $conn->query($query." LIMIT $startFrom, $limit");
        if (!$result) 
            echo "Wystąpił problem w zapytaniu";
        else {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (isset($_SESSION['userID']))
                        $owner = $_SESSION['userID'] == $row['user_id'];
                    else 
                        $owner = false;
                    echo "
                    <div class='postCard ".($firstExtended? 'first': null) ."' id='{$row['post_id']}'>
                        <div class='postCard-marks'>"
                            .($owner? "<div class='postCard-mark postCard-mark--owner'>Twój post</div>": null)." 
                        </div>
                        <div class='postCard-picture'>
                            <img src='{$row['thumbnail']}' alt='Post Picture'>
                            <div class='postCard-picture-sideButtons'>"
                                .($firstExtended? "<script> (window.innerWidth <= 881)? $('.first').removeClass('first-post'): $('.first').addClass('first-post') </script>": null)
                                .(isset($_SESSION['userID'])? "
                                    <button class='postCard-picture-sideButtons-button postCard-picture-sideButtons-button--like ".(isset($row['liked'])? "liked": null)."' onClick='likePost(this,{$row['post_id']})'>
                                        <span class='material-icons'>favorite</span>
                                    </button>
                                    <button class='postCard-picture-sideButtons-button postCard-picture-sideButtons-button--bookmark ".(isset($row['bookmarked'])? "bookmarked": null)."' onClick='bookmarkPost(this,{$row['post_id']})'>
                                        <span class='material-icons'>bookmark</span>
                                    </button>
                                ":null) ."
                                <button class='postCard-picture-sideButtons-button postCard-picture-sideButtons-button--share' onClick='sharePost({$row['post_id']})'>
                                    <span class='material-icons'>share</span>
                                </button>
                            </div>
                        </div>
                        <div class='postCard-title'>{$row['title']}</div>
                        <div class='postCard-description'>{$row['description']}</div>
                        <div class='postCard-footer'>
                            
                            <a href='$_IPADDR/user?id={$row['user_id']}'><div class='postCard-footer-author'>
                                <div class='avatarSVG'>{$row['avatar_svg']}</div>
                                {$row['username']} 
                            </div></a>

                            <div class='postCard-footer-date'>
                                <span class='material-icons'>date_range</span>"
                                .date("d-m-Y", $row['published_at']).
                            "</div>
                        </div>
                    </div>";
                    $firstExtended = false;
                }
            } else {
                echo "<h2>Brak wyników</h2>";
            }
        }
    
    ?>
</div>

<div class="pagination">
    <?php 

        $query = "SELECT count(post_id) as numberOfPosts FROM posts JOIN users on posts.user_id = users.user_id WHERE $userIDCondition AND $searchStringCondition AND $tagCondition AND posts.accepted = 1";
        $result = $conn->query($query);
        if (!$result) {
            echo "Błąd w zapytaniu (2)";
        } else {
            $numberOfPosts = $result->fetch_assoc()['numberOfPosts'];

            if (isset($_GET['searchString']) || isset($_GET['userID']) || isset($_GET['tag']) || $page > 1) {
                $numberOfPages = ceil(($numberOfPosts - 1) / $limit);
            } else {
                $numberOfPages = ceil($numberOfPosts / $limit);
            }

            $query = $_GET;
            unset($query['page']);
            $query_result = http_build_query($query);

            for($i = 1; $i <= $numberOfPages; $i++) {
                if ($i == $page)
                    echo "<a href='/?page=$i&$query_result' active>$i</a>";
                else 
                    echo "<a href='/?page=$i&$query_result'>$i</a>";
            } 
        }     
    ?>
</div>

<script>

    function replaceQueryParam(param, newval, search) {
        var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
        var query = search.replace(regex, "$1").replace(/&$/, '');

        return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
    }

    $('.option.searchString').click(()=>{
        let str = window.location.search
        str = replaceQueryParam('searchString', '', str)
        window.location = window.location.pathname + str
    })

    $('.option.tag').click(()=>{
        let str = window.location.search
        str = replaceQueryParam('tag', '', str)
        window.location = window.location.pathname + str
    })

    $('.option.user').click(()=>{
        let str = window.location.search
        str = replaceQueryParam('userID', '', str)
        window.location = window.location.pathname + str
    })

    $('#postsLimit').change((e)=>{
        let str = window.location.search
        str = replaceQueryParam('limit', e.currentTarget.value, str)
        window.location = window.location.pathname + str
    })

    $('#sortBy').change((e)=>{
        let str = window.location.search
        str = replaceQueryParam('sort', e.currentTarget.value, str)
        window.location = window.location.pathname + str
    })

    $('.sort-direction').click(()=>{
        if ($('.sort-direction').hasClass('ASC')) {
            let str = window.location.search
            str = replaceQueryParam('order', "DESC", str)
            window.location = window.location.pathname + str 
        } else {
            let str = window.location.search
            str = replaceQueryParam('order', "ASC", str)
            window.location = window.location.pathname + str 
        }
    })

    $(window).on('resize', ()=>{
        (window.innerWidth <= 872)? $('.first').removeClass('first-post'): $('.first').addClass('first-post')
    })

</script>