<form class="registerLoginbox" id="registerForm" autocomplete="off">
    <h2>Zarejestruj się</h2>

    <div class="register-avatar" onclick="showAvatarCustomization()">
        <div class="avatar"></div>
        <span class='material-icons'>settings</span>
    </div>
    <div class="modalBackground" id="modalBackground-avatar">
        <div class="modalWindow">
            <button type="button" onclick="showAvatarCustomization()" class="modalWindow-closeButton">
                <span class="material-icons">close</span>
            </button>
            <div class="avatar-preview"></div>
            <div class="avatar-customization">
                <div class='avatarSelect'>
                    <label for='top'>Włosy/Czapka</label>
                    <input type='range' value="0" min='0' max='34' name='top' id='top' />
                </div>
                <div class='avatarSelect'>
                    <label for='hairColor'>Kolor włosów</label>
                    <input type='range' value="0" min='0' max='9' name='hairColor' id='hairColor' />
                </div>
                <div class='avatarSelect'>
                    <label for='hatColor'>Kolor czapki</label>
                    <input type='range' value="0" min='0' max='14' name='hatColor' id='hatColor' />
                </div>
                <div class='avatarSelect'>
                    <label for='accessories'>Akcesoria</label>
                    <input type='range' value="-1" min='-1' max='5' name='accessories' id='accessories' />
                </div>
                <div class='avatarSelect'>
                    <label for='accessoriesColor'>Kolor akcesoriów</label>
                    <input type='range' value="-1" min='-1' max='5' name='accessoriesColor' id='accessoriesColor' />
                </div>
                <div class='avatarSelect'>
                    <label for='eyebrows'>Brwi</label>
                    <input type='range' value="1" min='0' max='13' name='eyebrows' id='eyebrows' />
                </div>
                <div class='avatarSelect'>
                    <label for='eyes'>Oczy</label>
                    <input type='range' min='0' max='5' name='eyes' id='eyes' />
                </div>
                <div class='avatarSelect'>
                    <label for='facialHair'>Zarost</label>
                    <input type='range' value="0" min='0' max='5' name='facialHair' id='facialHair' />
                </div>
                <div class='avatarSelect'>
                    <label for='facialHair'>Kolor zarostu</label>
                    <input type='range' value="0" min='0' max='9' name='facialHairColor' id='facialHairColor' />
                </div>
                <div class='avatarSelect'>
                    <label for='mouth'>Usta</label>
                    <input type='range' value="1" min='0' max='5' name='mouth' id='mouth' />
                </div>
                <div class='avatarSelect'>
                    <label for='skin'>Kolor skóry</label>
                    <input type='range' value="3" min='0' max='6' name='skin' id='skin' />
                </div>
                <div class='avatarSelect'>
                    <label for='clothing'>Ubranie</label>
                    <input type='range' value="3" min='0' max='8' name='clothing' id='clothing' />
                </div>
                <div class='avatarSelect'>
                    <label for='clothingColor'>Kolor ubrania</label>
                    <input type='range' value="3" min='0' max='14' name='clothingColor' id='clothingColor' />
                </div>
            </div>
            <div class="register-avatar-buttons">
                <button type="button" onclick="generateAvatar(random = true)">Losuj</button>
                <button onclick="showAvatarCustomization()" type="button">Zatwierdź</button>
            </div>
        </div>
    </div>


    <div class="textField">
        <input autocomplete="off" minlength="2" required id="firstName" type="text" name="firstName">
        <label for="firstName">Imię</label>
    </div>

    <div class="textField">
        <input autocomplete="off" minlength="2" required id="lastName" type="text" name="lastName">
        <label for="lastName">Nazwisko</label>
    </div>

    <div class="textField">
        <input autocomplete="off" minlength="5" required id="username" type="text" name="username">
        <label for="username">Nazwa użytkownika</label>
    </div>

    <div class="textField">
        <input autocomplete="off" required id="email" type="email" name="email">
        <label for="email">Adres e-mail</label>
    </div>

    <div class="textField">
        <input autocomplete="new-password" minlength="5" required id="password" type="password" name="password">
        <label for="password">Hasło</label>
    </div>

    <div class="textField">
        <input autocomplete="off" minlength="5" required id="passwordCheck" type="password" name="passwordCheck">
        <label for="passwordCheck">Powtórz hasło</label>
    </div>

    <div class="termAccept">
        <input required type="checkbox" name="termAccept" id="termAccept">
        <label for="termAccept">Akceptuję regulamin</label>
    </div>

    <input type="hidden" name="avatarSVG" id="avatarSVG">
    <input type="hidden" name="avatarOptions" id="avatarOptions">

    <input type="submit" value="Zarejestruj się">
</form>


<script>


function showAvatarCustomization(){
    $('#modalBackground-avatar').toggleClass('active')
}


function getCookie(name) {
    var cookieArr = document.cookie.split(";");
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        if(name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}

let currentAvatarOptionsNumeric = {}

if (getCookie('lastAvatar')) {
    currentAvatarOptionsNumeric = JSON.parse(getCookie('lastAvatar'))
    $('#top').val(currentAvatarOptionsNumeric.top)
    $('#hairColor').val(currentAvatarOptionsNumeric.hairColor)
    $('#hatColor').val(currentAvatarOptionsNumeric.hatColor)
    $('#accessories').val(currentAvatarOptionsNumeric.accessories)
    $('#accessoriesColor').val(currentAvatarOptionsNumeric.accessoriesColor)
    $('#eyebrows').val(currentAvatarOptionsNumeric.eyebrows)
    $('#eyes').val(currentAvatarOptionsNumeric.eyes)
    $('#facialHair').val(currentAvatarOptionsNumeric.facialHair)
    $('#facialHairColor').val(currentAvatarOptionsNumeric.facialHairColor)
    $('#mouth').val(currentAvatarOptionsNumeric.mouth)
    $('#skin').val(currentAvatarOptionsNumeric.skin)
    $('#clothing').val(currentAvatarOptionsNumeric.clothing)
    $('#clothingColor').val(currentAvatarOptionsNumeric.clothingColor)
} 

generateAvatar()


function generateAvatar( random = false, options = {} ){
    let top = Object.keys(Avataaars.paths.top)[$('#top').val()]
    let hairColor = Object.keys(Avataaars.colors.hair)[$('#hairColor').val()]
    let hatColor = Object.keys(Avataaars.colors.palette)[$('#hatColor').val()]
    let accessories = Object.keys(Avataaars.paths.accessories)[$('#accessories').val()]
    let accessoriesColor = Object.keys(Avataaars.colors.palette)[$('#accessoriesColor').val()]
    let eyebrow = Object.keys(Avataaars.paths.eyebrows)[$('#eyebrows').val()]
    let eyes = Object.keys(Avataaars.paths.eyes)[$('#eyes').val()]
    let facialHair = Object.keys(Avataaars.paths.facialHair)[$('#facialHair').val()]
    let facialHairColor = Object.keys(Avataaars.colors.hair)[$('#facialHairColor').val()]
    let mouth = Object.keys(Avataaars.paths.mouth)[$('#mouth').val()]
    let skin = Object.keys(Avataaars.colors.skin)[$('#skin').val()]
    let clothing = Object.keys(Avataaars.paths.clothing)[$('#clothing').val()]
    let clothingColor = Object.keys(Avataaars.colors.palette)[$('#clothingColor').val()]
    
    if (random) {
        let randomTop = Math.floor(Math.random() * 34)
        top = Object.keys(Avataaars.paths.top)[randomTop]
        $('#top').val(randomTop)

        let randomHairColor = Math.floor(Math.random() * 9)
        hairColor = Object.keys(Avataaars.colors.hair)[randomHairColor]
        $('#hairColor').val(randomHairColor)

        let randomHatColor = Math.floor(Math.random() * 14)
        hatColor = Object.keys(Avataaars.colors.palette)[randomHatColor]
        $('#hatColor').val(randomHatColor)

        let randomAccessories = Math.floor(Math.random() * 6)
        accessories = Object.keys(Avataaars.paths.accessories)[randomAccessories]
        $('#accessories').val(randomAccessories)

        let randomAccessoriesColor = Math.floor(Math.random() * 14)
        accessoriesColor = Object.keys(Avataaars.colors.palette)[randomAccessoriesColor]
        $('#accessoriesColor').val(randomAccessoriesColor)

        let randomEyebrow = Math.floor(Math.random() * 12)
        eyebrow = Object.keys(Avataaars.paths.eyebrows)[randomEyebrow]
        $('#eyebrows').val(randomEyebrow)

        let randomEyes = Math.floor(Math.random() * 11)
        eyes = Object.keys(Avataaars.paths.eyes)[randomEyes]
        $('#eyes').val(randomEyes)

        let randomFacialHair = Math.floor(Math.random() * 6)
        facialHair = Object.keys(Avataaars.paths.facialHair)[randomFacialHair]
        $('#facialHair').val(randomFacialHair)

        let randomFacialColor = Math.floor(Math.random() * 9)
        facialHairColor = Object.keys(Avataaars.colors.hair)[randomFacialColor]
        $('#facialHairColor').val(randomFacialColor)

        let randomMouth = Math.floor(Math.random() * 11)
        mouth = Object.keys(Avataaars.paths.mouth)[randomMouth]
        $('#mouth').val(randomMouth)

        let randomSkin = Math.floor(Math.random() * 6)
        skin = Object.keys(Avataaars.colors.skin)[randomSkin]
        $('#skin').val(randomSkin)

        let randomClothing = Math.floor(Math.random() * 8)
        clothing = Object.keys(Avataaars.paths.clothing)[randomClothing]
        $('#clothing').val(randomClothing)

        let randomClothingColor = Math.floor(Math.random() * 9)
        clothingColor = Object.keys(Avataaars.colors.palette)[randomClothingColor]
        $('#clothingColor').val(randomClothingColor)
    }

    if(Avataaars._getTopType(top)[1]) {
        $('#hatColor').attr('disabled', false)
        $('#hatColor').parent().removeClass('disabled')
        $('#hairColor').attr('disabled', true)
        $('#hairColor').parent().addClass('disabled')
    } else {
        $('#hatColor').attr('disabled', true)
        $('#hatColor').parent().addClass('disabled')
        $('#hairColor').attr('disabled', false)
        $('#hairColor').parent().removeClass('disabled')
    }

    if($('#facialHair').val() == 0) {
        $('#facialHairColor').attr('disabled', true)
        $('#facialHairColor').parent().addClass('disabled')
    } else {
        $('#facialHairColor').attr('disabled', false)
        $('#facialHairColor').parent().removeClass('disabled')
    }

    if($('#accessories').val() == -1) {
        $('#accessoriesColor').attr('disabled', true)
        $('#accessoriesColor').parent().addClass('disabled')
    } else {
        $('#accessoriesColor').attr('disabled', false)
        $('#accessoriesColor').parent().removeClass('disabled')
    }

    currentAvatarOptionsNumeric = {
        top: $('#top').val(),
        hairColor: $('#hairColor').val(),
        hatColor: $('#hatColor').val(),
        accessories: $('#accessories').val(),
        accessoriesColor: $('#accessoriesColor').val(),
        eyebrows: $('#eyebrows').val(),
        eyes: $('#eyes').val(),
        facialHair: $('#facialHair').val(),
        facialHairColor: $('#facialHairColor').val(),
        mouth: $('#mouth').val(),
        skin: $('#skin').val(),
        clothing: $('#clothing').val(),
        clothingColor: $('#clothingColor').val(),
    }

    options = {
        style: 'circle',
        top,
        hairColor,
        hatColor,
        accessories,
        accessoriesColor,
        eyes,
        eyebrow,
        facialHair,
        facialHairColor,
        mouth,
        skin,
        clothing,
        clothingColor
    }

    document.cookie = `lastAvatar=${JSON.stringify(currentAvatarOptionsNumeric)}`

    $('.register-avatar .avatar').html(
        Avataaars.create(options)
    )

    $('.avatar-preview').html(
        Avataaars.create(options)
    )

    $('#avatarSVG').val(Avataaars.create(options))
    $('#avatarOptions').val(JSON.stringify(currentAvatarOptionsNumeric))

    return Avataaars.create(options)
}


$('.avatarSelect input').on('input', ()=>{
    $('.avatar-preview').html(
        generateAvatar()
    )
})

$('#modalBackground-avatar').click(function(event){
    if($(event.target).is('#modalBackground-avatar')) {
        $(event.target).removeClass('active')
    }
})

$('#registerForm').on('submit', (e)=>{
    e.preventDefault()
    const data = $('#registerForm').serialize()

    if ($('#password').val() !== $('#passwordCheck').val()) {
        Snackbar.show({text: 'Hasła różnią się', customClass: 'snackbar error'})
        return
    }

    $.post("../helpers/registerUser.php", data, function (res) {
        if(res == "success") {
            location.replace('<?php echo $_IPADDR?>/login?registerSuccess=true')
        } else {
            Snackbar.show({text: res, customClass: 'snackbar error'})
        }
    });  
})



</script>