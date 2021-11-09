<script>
    const getUsers = page => {
        countUsers()
        $.get("/helpers/admin/getUsers.php", {page: getUsers_currentPage}, res => {
            $('.content').html('')
            $('.content').append('<h2>Użytkownicy</h2>')
            $('.content').append(res)
            $('.content').append(`
                <div class='pagination-container'>
                    <div class='pagination'>
                        <button class='previous-page' onclick='getUsers_previousPage()'>
                            <span class='material-icons'>navigate_before</span>
                        </button>
                        <p>Strona ${getUsers_currentPage} z ${getUsers_totalPages}</p>
                        <button class='next-page' onclick='getUsers_nextPage()'>
                            <span class='material-icons'>navigate_next</span>
                        </button>
                    </div>
                </div>`)

                if (getUsers_currentPage <= 1 ) {
                $('.previous-page').attr('disabled', 'true')
            }

            if (getUsers_currentPage == getUsers_totalPages) {
                $('.next-page').attr('disabled', 'true')
            }

            if (getUsers_totalPages == 1) {
                $('.next-page').attr('disabled', 'true')
                $('.previous-page').attr('disabled', 'true')
            }
        })
    }

    
    let getUsers_totalPages = 0;
    let getUsers_currentPage = 1;

    const getUsers_previousPage = () => {
        getUsers_currentPage --;
        getUsers(getUsers_currentPage)
    }

    const getUsers_nextPage = () => {
        getUsers_currentPage ++;
        getUsers(getUsers_currentPage)
    }

    const countUsers = () => {
        $.get("/helpers/admin/getUsers.php", res=> {
            getUsers_totalPages = Math.ceil(res/5);
            if (getUsers_totalPages == 0) {
                getUsers_currentPage = 0;
            }
        }) 
    }

    const resetPassword = id => {
        $.get("/helpers/admin/resetPassword.php", {id}, res => {
            if (res == 'success') {
                $('button#resetPassword .material-icons').html("done");
                Snackbar.show({text: "Hasło zostało zresetowane", customClass: 'snackbar'})
            } else {
                Snackbar.show({text: res, customClass: 'snackbar'})
            }
        })
        closeDialog();
    }

    const resetPasswordDialog = id => {
        $('body').append(`
            <div class='modalBackground active'>
                <div class="modalWindow accept">
                    <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                    <div class='modalWindow-title'>Potwierdzenie</div>
                    <div class='buttons'>
                        <button onclick='resetPassword(${id})'>Zresetuj hasło</button>
                        <button onclick='closeDialog()'>Anuluj</button>
                    </div>
                </div>
            </div>`)
    }

    const banUser = (option, id) => {
        if (option == 'unban') {
            $.get("/helpers/admin/unbanUser.php", {id}, res => {
                $("button#banUser .text").html("Zablokuj");
                $("button#banUser .material-icons").html("lock");
            })
        } else {
            $.get("/helpers/admin/banUser.php", {id}, res => {
                $("button#banUser .text").html("Odblokuj");
                $("button#banUser .material-icons").html("lock_open");
            })
        }
        closeDialog();
    }

    const banUserDialog = id => {
        if ($("button#banUser .text").html() == "Odblokuj") {
            $('body').append(`
            <div class='modalBackground active'>
                <div class="modalWindow accept">
                    <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                    <div class='modalWindow-title'>Potwierdzenie</div>
                    <div class='buttons'>
                        <button onclick='banUser("unban", ${id})'>Odblokuj</button>
                        <button onclick='closeDialog()'>Anuluj</button>
                    </div>
                </div>
            </div>`)
        } else {
            $('body').append(`
            <div class='modalBackground active'>
                <div class="modalWindow accept">
                    <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                    <div class='modalWindow-title'>Potwierdzenie</div>
                    <div class='buttons'>
                        <button onclick='banUser("ban", ${id})'>Zablokuj</button>
                        <button onclick='closeDialog()'>Anuluj</button>
                    </div>
                </div>
            </div>`)
        }
    }

    const sendWarning = id => {
        const reason = $('#warningReason').val();
        $.get("/helpers/admin/sendWarning.php", {reason, id}, res => {
            if (res == 'success') {
                Snackbar.show({text: "Ostrzeżenie zostało wysłane", customClass: 'snackbar'})
            } else {
                Snackbar.show({text: res, customClass: 'snackbar'})
            }
        })
    }

    const warningDialog = id => {
        $('body').append(`
            <div class='modalBackground active'>
                <div class="modalWindow warning">
                    <button type="button" onclick="closeModal()" class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                    <div class='modalWindow-title'>Ostrzeżenie</div>
                    <textarea id='warningReason' placeholder='Powód ostrzeżenia...'></textarea>
                    <div class='buttons'>
                        <button onclick='sendWarning(${id})'>Wyślij</button>
                        <button onclick='closeDialog()'>Anuluj</button>
                    </div>
                </div>
            </div>`)
    }
</script>