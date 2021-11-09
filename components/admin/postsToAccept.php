<script>
const getPostsToAccept = page => {
    $.get("../helpers/admin/getPostsToAccept.php", {page: getPostsToAccept_currentPage}, res => {
        $('.content').html('')
        $('.content').append('<h2>Posty do akceptacji</h2>')
        $('.content').append(res)
        $('.content').append(`
            <div class='pagination-container'>
                <div class='pagination'>
                    <button class='previous-page' onclick='previousPage()'>
                        <span class='material-icons'>navigate_before</span>
                    </button>
                    <p>Strona ${getPostsToAccept_currentPage} z ${totalPages}</p>
                    <button class='next-page' onclick='nextPage()'>
                        <span class='material-icons'>navigate_next</span>
                    </button>
                </div>
            </div>`)

        if (getPostsToAccept_currentPage <= 1 ) {
            $('.previous-page').attr('disabled', 'true')
        }

        if (getPostsToAccept_currentPage == totalPages) {
            $('.next-page').attr('disabled', 'true')
        }

        if (totalPages == 1) {
            $('.next-page').attr('disabled', 'true')
            $('.previous-page').attr('disabled', 'true')
        }
    }) 
}

const previousPage = () => {
    getPostsToAccept_currentPage --;
    getPostsToAccept(getPostsToAccept_currentPage)
}

const nextPage = () => {
    getPostsToAccept_currentPage ++;
    getPostsToAccept(getPostsToAccept_currentPage)
}

let totalPages = 1;

    const countPostsToAccept = () => {
        $.get("../helpers/admin/getPostsToAccept.php", res=>{
            totalPages = Math.ceil(res/4);
            if (totalPages == 0) {
                getPostsToAccept_currentPage = 0;
            }
        }) 
    }

    let getPostsToAccept_currentPage = 1;
    
    countPostsToAccept()


    let confirmPostAccept = 0;

    const acceptPost = (bool, id = 0) => {
        accepted = bool;
        if ($('#confirmPostAccept:checked')) {
            $.get('/helpers/admin/changeUserSettings.php', {confirmPostAccept: 1});
            confirmPostAccept = 1;
        }
        if (accepted) {
            $.get('/helpers/admin/acceptPost.php', {id}, res=>{
                if (res == 'success') {
                    $('.modalBackground').remove()
                    Snackbar.show({text: 'Post zaakceptowany', customClass: 'snackbar'})
                    getPostsToAccept(getPostsToAccept_currentPage);
                } else {
                    Snackbar.show({text: res, customClass: 'snackbar'})
                }
            })
        } else {
            $('.modalBackground').remove()
        }
    }

    const acceptPostDialog = id => {
        if ('<?php echo $_SESSION['confirmPostAccept'] ?>' == 1 || confirmPostAccept == 1) {
            acceptPost(true, id);
        } else {
            $('body').append(`
            <div class='modalBackground active'>
                <div class="modalWindow accept-post">
                    <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                    <div class='modalWindow-title'>Potwierdzenie</div>
                    <div class='accept-buttons'>
                        <button id='accept' onclick='acceptPost(true, ${id})'>Zaakceptuj</button>
                        <button onclick='acceptPost(false)'>Anuluj</button>
                    </div>
                    <label><input id='confirmPostAccept' type='checkbox'>Nie pokazuj ponownie</label>
                </div>
            </div>`)
        }
    }


    const discardPost = id => {
        const reason = $('.reason textarea').val();
        $.get('/helpers/admin/discardPost.php', {id, reason}, res => {
            if (res == 'success') {
                $('.modalBackground').remove()
                Snackbar.show({text: 'Post odrzucony', customClass: 'snackbar'})
                getPostsToAccept(getPostsToAccept_currentPage);
            } else {
                Snackbar.show({text: res, customClass: 'snackbar'})
            }
        })
    }

    const closeDialog = () => {
        $('.modalBackground').remove();
    }

    const discardPostDialog = id => {
        $('body').append(`
        <div class='modalBackground active'>
            <div class="modalWindow discard-post">
                <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                <div class='modalWindow-title'>Odrzucenie posta</div>
                <div class='reason'>
                    <textarea placeholder='Podaj powód...'></textarea>
                </div>
                <div class='accept-buttons'>
                    <button id='discard' onclick='discardPost(${id})'>Odrzuć</button>
                    <button onclick='closeDialog()'>Anuluj</button>
                </div>
            </div>
        </div>`)
    }
    </script>