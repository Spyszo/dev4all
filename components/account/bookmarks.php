<p class="item-title">Zakładki</p>
<div class="bookmarks-list">

</div>
<div class="bookmarks-getMoreButton">
    <button onclick="getMoreBookmarks()">Pokaż więcej</button>
</div>

<script>
    let bookmarksPage = 1
    
    const getMoreBookmarks = () => {
        bookmarksPage++
        $.post("/helpers/getBookmarks.php", {bookmarksPage}, res => {
            if (res == "<p>To wszystko...</p>") {
                $('.bookmarks-list').append(res)
                $('.bookmarks-getMoreButton button').remove()
            } else {
                $('.bookmarks-list').append(res)
            }
        }); 
    }


    $.post("/helpers/getBookmarks.php", {bookmarksPage}, res => {
        if (res == "<p>To wszystko...</p>") {
            $('.bookmarks-list').append(res)
            $('.bookmarks-getMoreButton button').remove()
        } else {
            $('.bookmarks-list').append(res)
        }
    })

    const removeBookmark = (e, id) => {
        $.get("/helpers/account/removeBookmark.php", {id}, res => {
            if (res == 'success') {
                $(e).parent().parent().remove();
                Snackbar.show({text: "Dodano nowy post.", customClass: 'snackbar'})
            } else {
                Snackbar.show({text: res, customClass: 'snackbar'})
            }
        })
    }
</script>