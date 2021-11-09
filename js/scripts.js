const likePost = (e, postID) => {
    if($(e).hasClass('liked')) {
        $.post('../helpers/removeLike.php', { postID }, res => {
            if (res == 'success') {
                $(e).removeClass('liked');
                $('.post-title-stats-likes .likes-count').html(parseInt($('.post-title-stats-likes .likes-count').html()) - 1)
            } else {
                console.log(res)
            }
        }); 
    } else {
        $.post('../helpers/addLike.php', { postID }, res => {
            if (res == 'success') {
                $(e).addClass('liked');
                $('.post-title-stats-likes .likes-count').html(parseInt($('.post-title-stats-likes .likes-count').html(), 10) + 1)
            } else {
                console.log(res)
            }
        }); 
    }
}

const bookmarkPost = (e, postID) => {
    if ($(e).hasClass('bookmarked')) {
        $.post('../helpers/removeBookmark.php', { postID }, res => {
            if (res == 'success') {
                $(e).removeClass('bookmarked');
            } else {
                console.log(res)
            }
        }); 
    } else {
        $.post('../helpers/addBookmark.php', { postID }, res =>{
            if (res == 'success') {
                $(e).addClass('bookmarked');
            } else {
                console.log(res)
            }
        }); 
    }
}

const sharePost = postID => {
    $('body').append(`                 
    <div class='modalBackground active' id='post-share-modal'>
        <div class='modalWindow'>
            <div class='modalWindow-topBar'>
                <div class='modalWindow-title'>Udostępnij</div>
                <div class='modalWindow-closeButton' onClick='closeModal()'><span class='material-icons'>close</span></div>
            </div>

            <div class='post-share-copyLink'>
                <input aria-label='Share Link' readonly type='text' value='${_IPADDR}/post/postID=${postID}'/>
                <button class='copyButton' onclick='copy("${_IPADDR}/post/postID=${postID}")'><span class='material-icons'>copy</span></button>
            </div>
        </div>
    </div>`
    )
}

const copy = () => {
    const copyText = $('.post-share-copyLink input');

    copyText.select();

    document.execCommand("copy");

    $('.copyButton').addClass('copied');
    Snackbar.show({text: "Skopiowano do schowka", customClass: 'snackbar success'})
}

$('html').on('click', '.modalBackground', (event)=>{
    if ($(event.target).is('.modalBackground') && !$(event.target).is('#modalBackground-avatar')) {
        $('.modalBackground').remove();  
    } 
})

const closeModal = () => {
    $('.modalBackground').remove(); 
}

$('.postCard').click((e) => {
    if($(e.target).is('.postCard-picture') || $(e.target).is('.postCard-title') || $(e.target).is('.postCard-description')) {
        console.log(e)
        window.location = _IPADDR + "/post?id=" + e.currentTarget.id;
    }
})
