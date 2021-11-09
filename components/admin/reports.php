<script>
    const getReports = () => {
        countReports()
        $.get("../helpers/admin/getReports.php", {page: getReports_currentPage}, res=>{
            $('.content').html('')
            $('.content').append('<h2>Zgłoszenia</h2>')
            $('.content').append('<div class="report-content">Wybierz zgłoszenie</div>')
            $('.content').append(res)
            $('.content').append(`
                <div class='pagination-container'>
                    <div class='pagination'>
                        <button class='previous-page' onclick='getReports_previousPage()'>
                            <span class='material-icons'>navigate_before</span>
                        </button>
                        <p>Strona ${getReports_currentPage} z ${getReports_totalPages}</p>
                        <button class='next-page' onclick='getReports_nextPage()'>
                            <span class='material-icons'>navigate_next</span>
                        </button>
                    </div>
                </div>`)

            if (getReports_currentPage <= 1 ) {
                $('.previous-page').attr('disabled', 'true')
            }

            if (getReports_currentPage == getReports_totalPages) {
                $('.next-page').attr('disabled', 'true')
            }

            if (getReports_totalPages == 1) {
                $('.next-page').attr('disabled', 'true')
                $('.previous-page').attr('disabled', 'true')
            }
        }) 

        getOneReport(0);
    }


    const getOneReport = id => {
        $.get('/helpers/admin/getOneReport.php', {id}, res => {
            $('.report-content').html(res);
        })
    }

    const getReports_previousPage = () => {
        getReports_currentPage --;
        getReports(getReports_currentPage)
    }

    const getReports_nextPage = () => {
        getReports_currentPage ++;
        getReports(getReports_currentPage)
    }

    let getReports_totalPages = 1;

    const countReports = () => {
        $.get("../helpers/admin/getReports.php", res=>{
            getReports_totalPages = Math.ceil(res/5);
            if (getReports_totalPages == 0) {
                getReports_currentPage = 0;
            }
        }) 
    }

    let getReports_currentPage = 1;
    
    countReports()

    const acceptReport = (type, ) => {
        accepted = bool;
        if (accepted) {
            $.get('/helpers/admin/acceptPost.php', {id}, res=>{
                if (res == 'success') {
                    $('.modalBackground').remove()
                    Snackbar.show({text: 'Post zaakceptowany', customClass: 'snackbar'})
                    getReports(getReports_currentPage);
                } else {
                    Snackbar.show({text: res, customClass: 'snackbar'})
                }
            })
        } else {
            $('.modalBackground').remove()
        }
    }

    const acceptReportDialog = id => {

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


    const discardReport = id => {
        const reason = $('.reason textarea').val();
        $.get('/helpers/admin/discardPost.php', {id, reason}, res => {
            if (res == 'success') {
                $('.modalBackground').remove()
                Snackbar.show({text: 'Post odrzucony', customClass: 'snackbar'})
                getReports(getReports_currentPage);
            } else {
                Snackbar.show({text: res, customClass: 'snackbar'})
            }
        })
    }

    const discardReportDialog = id => {
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