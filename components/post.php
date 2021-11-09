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

    parse_str($_SERVER['QUERY_STRING'], $options);
    $postID = $options['id'];

    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        $query = "SELECT posts.*, users.user_id, users.first_name, users.last_name, users.avatar_svg, (SELECT Count(post_id) FROM likes WHERE likes.post_id = posts.post_id) AS likes_count, (SELECT 1 FROM likes WHERE likes.user_id = $userID AND likes.post_id = posts.post_id) AS liked, (SELECT 1 FROM bookmarks WHERE bookmarks.user_id = $userID AND bookmarks.post_id = posts.post_id) AS bookmarked FROM posts JOIN users ON posts.user_id = users.user_id WHERE posts.post_id = $postID";
    } else {
        $query = "SELECT posts.*, users.user_id, users.first_name, users.last_name, users.avatar_svg, (SELECT Count(post_id) FROM likes WHERE likes.post_id = posts.post_id) AS likes_count FROM posts JOIN users ON posts.user_id = users.user_id WHERE posts.post_id = $postID";
    }

    $result = $conn->query($query);
    if (!$result) {
        echo "Wystąpił błąd podczas wczytywania posta...";
    } else {
        $post = $result->fetch_assoc();

        if ($post['accepted'] == 0 && (!isset($_SESSION['userID']) || $_SESSION['admin'] != 1)) {
            echo "<h1>Nie możesz zobaczyć tego posta, ponieważ nie został on jeszcze zweryfikowany.</h1>";
            return;
        }

        $liked = '';
        if (isset($post['liked'])) 
            $liked = 'liked';

        $bookmarked = '';
        if (isset($post['bookmarked'])) 
            $bookmarked = 'bookmarked';
    ?>

        <head>
            <link rel="stylesheet" href="../css/post.css">
        </head>

        <div class="post">
            <div class="post-title">
                <div class="post-image">
                    <img src=" <?php echo $post['image']?> "/>
                </div>
                <div class="post-title-menu">
                    <div class="post-title-author">
                        <a href="/user?<?php echo $post['user_id']; ?>"> 
                            <div class="post-title-author-avatar avatar"><?php echo $post['avatar_svg']; ?></div>
                            <div class="post-title-author-name"><?php echo $post['first_name'] ." " .$post['last_name'] ?></div>
                        </a>
                    </div>
                    <div class="post-title-stats">
                        <button class="post-title-stats-share" onclick="sharePost(<?php echo $postID?>)"><span class='material-icons'>share</span></button>
                        <?php if (isset($_SESSION['userID'])) { ?>
                        <button class="post-title-stats-report" onclick='reportPostDialog()'><span class='material-icons'>report</span></button>
                        <button class="post-title-stats-likes <?php echo $liked; ?>" onclick="likePost(this, '<?php echo $post['post_id']?>')"><span class='likes-count'><?php echo $post['likes_count'] ?></span> <span class='material-icons'>favorite</span></button>
                        <button class="post-title-stats-bookmarks <?php echo $bookmarked; ?>" onclick="bookmarkPost(this, '<?php echo $post['post_id']?>')"><span class='material-icons'>bookmark</span></button>         
                        <?php } ?>
                    </div>
                </div>
            </div>
            <h1><hr><span><?php echo $post['title']?></span><hr></h1>

            <div class="post-content"> <?php echo $post['body']?> </div>

            <div class="post-comments"> 
                <h1><hr><span>Komentarze</span><hr></h1>

                <?php if (isset($_SESSION['userID'])) { ?>
                    <form class="addCommentForm" method="post">
                        <div class="avatar"><?php echo $_SESSION['avatarSVG'] ?></div>
                        <textarea required placeholder="abc..." id="formCommentBody"></textarea>
                        <input type="submit" value="Dodaj komentarz"/>
                    </form>
                <?php }

                    $query = "SELECT comments.*, users.user_id, users.username, users.avatar_svg FROM comments JOIN users on comments.user_id = users.user_id WHERE comments.post_id = $postID ORDER BY comments.comment_id DESC";

                    $result = $conn->query($query);
                    
                    if (!$result) echo "Błąd podczas wczytywania komentarzy...";
                    else {
                        while($comment = $result->fetch_assoc()) { 
                            if ($comment['parent_id'] == 0) {
                                $commentID = $comment['comment_id'];
                                $avatar = $comment['avatar_svg'];
                                $author = $comment['username'];
                                $publishedAt = getPostDate($comment['published_at']);
                                $body = $comment['body'];
                                echo "
                                <div>
                                    <div class='comment'>
                                        <div class='comment-avatar'>
                                            $avatar
                                        </div>
                                        <div class='comment-author'>$author</div>
                                        <div class='comment-date'>
                                            ";
                                            if (isset($_SESSION['userID']) && $_SESSION['admin'] == 1)
                                                echo "<span onclick='deleteComment(this, $commentID)' class='material-icons'>delete</span>";
                                            echo "
                                            $publishedAt
                                        </div>
                                        <div class='comment-body'>$body</div>
                                    </div>";

                                    $queryForReplies = "SELECT comments.*, users.user_id, users.username, users.avatar_svg FROM comments JOIN users on comments.user_id = users.user_id WHERE comments.parent_id = $commentID";
                                    $resultOfReply = $conn->query($queryForReplies);
                                    if (!$resultOfReply) echo "Błąd podczas wczytywania odpowiedzi...";
                                    else {
                                        while($commentReply = $resultOfReply->fetch_assoc()) { 
                                            $avatar = $commentReply['avatar_svg'];
                                            $author = $commentReply['username'];
                                            $publishedAt = getPostDate($commentReply['published_at']);
                                            $replyID = $commentReply['comment_id'];
                                            $body = $commentReply['body'];
                                            echo "
                                            <div class='commentReply'>
                                                <div class='comment-avatar'>
                                                    $avatar
                                                </div>
                                                <div class='comment-author'>$author</div>
                                                <div class='comment-date'>
                                                    ";
                                                    if (isset($_SESSION['userID']) && $_SESSION['admin'] == 1)
                                                        echo "<span onclick='deleteComment(this, $commentID)' class='material-icons'>delete</span>";
                                                    echo "
                                                    $publishedAt
                                                </div>
                                                <div class='comment-body'>$body</div>
                                            </div>";
                                        }
                                        if (isset($_SESSION['userID'])) echo "<button class='comment-replyButton' onClick='openReplyBox(this, $commentID)'><span class='material-icons'>reply</span>Odpowiedz</button>";
                                    }
                                echo "</div>";
                            }
                        }
                    }
                ?>
            </div>
        </div>

        <script>
            const addLocalComment = (avatarSVG, author, publishedAt, body, commentID) => {
                const comment = `
                    <div>
                        <div class='comment'>
                            <div class='comment-avatar'>
                                ${ avatarSVG }
                            </div>
                            <div class='comment-author'>${ author }</div>
                            <div class='comment-date'><span onclick='deleteComment(this, ${ commentID })' class='material-icons'>delete</span>${ publishedAt }</div>
                            <div class='comment-body'>${ body }</div>
                        </div>
                        <button class='comment-replyButton' onClick='openReplyBox(this, ${ commentID })'>Odpowiedz</button>
                    </div> 
                `;
                $(comment).insertAfter($('.addCommentForm'))
            }

            const addLocalCommentReply = (e, avatarSVG, author, publishedAt, body, commentID) => {
                const commentReply = `
                    <div class='commentReply'>
                        <div class='comment-avatar'>
                            ${ avatarSVG }
                        </div>
                        <div class='comment-author'>${ author }</div>
                        <div class='comment-date'> <span onclick='deleteComment(this, ${ commentID })' class='material-icons'>delete</span> ${ publishedAt }</div>
                        <div class='comment-body'>${ body }</div>
                    </div>   
                `;
                $(commentReply).insertBefore($(e).children('button'))
            }

            const openReplyBox = (e, commentID) => {
                if (!$(e).parent().has('.replyForm').length) {
                    $(e).parent().append(`
                    <form class="replyForm" method="post" onsubmit="return false">
                        <textarea placeholder="Odpowiedz na komentarz..."></textarea>
                        <button type="submit" onclick="replyComment(this, ${commentID})"><span class='material-icons'>send</span></button>
                    </form>`);
                } else {
                    $(e).parent().children('.replyForm').remove();
                }
            }

            const replyComment = (e, commentID) => {
                const body = $(e).parent().children('textarea').val()

                if (body.length < 1) return

                const avatarSVG = `<?php echo $_SESSION['avatarSVG'] ?>`
                const author = `<?php echo $_SESSION['username'] ?>`
                const publishedAt = '1 sekundę temu'
                const postID = `<?php echo $postID ?>`
                const userID = `<?php echo $_SESSION['userID'] ?>`

                $.post("../helpers/addCommentReply.php", { postID, commentID, body }, response => {
                    if (response >= 0) { 
                        const replyID = response;
                        $('.replyForm textarea').val('');
                        Snackbar.show({ text: 'Dodano odpowiedź!', customClass: 'snackbar' })
                        addLocalCommentReply($(e).parent().parent(), avatarSVG, author, publishedAt, body, replyID);
                    } else {
                        Snackbar.show({ text: 'Wystąpił błąd', customClass: 'snackbar' })
                    }
                });  
            }

            $('.addCommentForm').submit((e) => {
                e.preventDefault()
                const postID = '<?php echo $postID ?>'
                const body = $('#formCommentBody').val()
                if (body.length < 1) return
                const avatarSVG = `<?php echo $_SESSION['avatarSVG']?>`
                const publishedAt = '1 sekundę temu'
                const author = '<?php echo $_SESSION['username'] ?>'

                $.post("../helpers/addComment.php", { postID, body }, response => {
                    console.log(response)
                    if (response >= 0) {
                        Snackbar.show({ text: 'Dodano komentarz!', customClass: 'snackbar' })
                        const commentID = response;
                        addLocalComment(avatarSVG, author, publishedAt, body, commentID)
                        $('#formCommentBody').val('')
                    } else {
                        Snackbar.show({ text: 'Wystąpił błąd', customClass: 'snackbar' })
                    }
                });           
            })

            const closeDialog = () => {
                $('.modalBackground').remove();
            }

            const reportPost = (id) => {
                const reason = $('.reason textarea').val();
                $.get("../helpers/reportPost.php", {id, reason}, res => {
                    if (res == 'success') {
                        Snackbar.show({ text: 'Wysłano zgłoszenie', customClass: 'snackbar' });
                        closeDialog();
                    } else {
                        Snackbar.show({ text: res, customClass: 'snackbar' })
                    }
                })
            }

            const reportPostDialog = () => {
                const postID = <?php echo $postID?>;
                $('body').append(`
                    <div class='modalBackground active'>
                        <div class="modalWindow report-post">
                            <button type="button" onclick='closeModal()' class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                            <div class='modalWindow-title'>Zgłoś post</div>
                            <div class='reason'>
                                <textarea placeholder='Powód zgłoszenia...'></textarea>
                            </div>
                            <div class='report-buttons'>
                                <button id='report' onclick='reportPost(${postID})'>Zgłoś</button>
                                <button onclick='closeModal()'>Anuluj</button>
                            </div>
                        </div>
                    </div>`)
            }

            const deleteComment = (e, id) => {
                $.get("/helpers/admin/removeComment.php", {id}, res => {
                    if (res == 'success') {
                        Snackbar.show({ text: 'Usunięto komentarz', customClass: 'snackbar' });
                        if ($(e).parent().parent().is('.comment')) {
                            $(e).parent().parent().parent().remove()
                        } else {
                            $(e).parent().parent().remove()
                        }
                    } else {
                        Snackbar.show({ text: res, customClass: 'snackbar' });
                    }
                })
            }
        </script>
    <?php } ?>