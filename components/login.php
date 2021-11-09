<form class="registerLoginbox" id="loginForm">
    <h2>Zaloguj się</h2>

    <div class="textField">
        <input id="username" type="username" name="username">
        <label for="username">Nazwa użytkownika</label>
    </div>

    <div class="textField">
        <input id="password" type="password" name="password">
        <label for="password">Hasło</label>
    </div>

    <input type="submit" value="Zaloguj się">
</form>

<script>

    if (window.location.search.includes('registerSuccess=true'))
        Snackbar.show({text: "Zarejestrowano pomyślnie", customClass: 'snackbar success'})

    $('#loginForm').submit((e)=>{
        e.preventDefault()
        const data = $('#loginForm').serialize()

        $.post("../helpers/loginUser.php", data, function (res) {
            console.log(res)
            if (res == "success") {
                location.replace('<?php echo $_IPADDR?>')
            }
            else if (res == "banned") {
                $('body').append(`
                    <div class='modalBackground active'>
                        <div class="modalWindow info">
                            <button type="button" class="modalWindow-closeButton"></button>
                            <div class='modalWindow-title'>Powiadomienie</div>
                            <p>Twoje konto zostało zablokowane.</p>
                        </div>
                    </div>`)
            } else {
                Snackbar.show({text: res, customClass: 'snackbar error'})
            }
        });  
    })
</script>