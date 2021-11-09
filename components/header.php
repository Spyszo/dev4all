<header>
    <div>
        <a href="/" id="title">DEV4ALL</a>
        <button onclick="showSearchPanel()" class="search-button">
            <span class='material-icons'>search</span>
        </button>
    </div>
    <nav>
        <?php 
            if (!isset($_SESSION['userID'])) {
                echo "
                    <a class='nav-button' href='/login'>Zaloguj się</a>
                    <a class='nav-button nav-button--contained' href='/register'>
                        <span class='material-icons'>account_circle</span>
                        Zarejestruj się
                    </a>
                ";
            } else {
                $avatarSVG = $_SESSION["avatarSVG"];
                echo "
                    <a class='nav-button' href='/addPost'>Dodaj post</a>
                    <a class='accountButton avatar' id='accountButton' onClick='toggleAccountMenu()'>$avatarSVG</a>
                ";
            }
        ?>

        <div class="accountMenu">
            <a onclick="changeTheme()" id="darkMode_button"><span class='material-icons'>dark_mode</span>Zmień motyw</a>
            <a href="/addPost" id="addPost_button"><span class='material-icons'>post_add</span>Dodaj post</a>
            <a href="/account" id="profile_button"><span class='material-icons'>face</span>Profil</a>        
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) { ?>
            <a href="/adminPanel" id="adminPanel_button"><span class='material-icons'>admin_panel_settings</span>Panel administracyjny</a>
            <?php } ?>
            <a href="/logout" id="logout_button"><span class='material-icons'>logout</span>Wyloguj</a>
        </div>
    </nav>
</header>

<div class="searchPanel">
    <div class="searchPanel-icon">
        <span class='material-icons'>search</span>
    </div>
    <form action="/" method="GET">
        <input id="searchString" name="searchString">
    </form>
    <button class="close" onclick="showSearchPanel()">
        <span class='material-icons'>close</span>
    </button>
</div>
<div class="searchPanel-background">
    <div class="posts"></div>
    <div class="users"></div>
</div>

<script>

    
    const changeTheme = () => {
        if ($('html').hasClass('darkMode')) {
            $('html').removeClass('darkMode');
            Cookies.set('darkMode', false, { expires: 30000 })
        } else {
            $('html').addClass('darkMode');
            Cookies.set('darkMode', true, { expires: 30000 })
        }
    }

    const goToPost = id => {
        window.location = `/post?id=${id}`
    }

    const goToUser = id => {
        window.location = `/user?id=${id}`
    }

    const showSearchPanel = () => {
        $('.searchPanel-background').toggleClass('active')
        $('.searchPanel').toggleClass('active')
        $('.searchPanel input').focus();

        if ($('body').hasClass('modalOpen')) {
            setTimeout(() => $('body').removeClass('modalOpen'), 500)
        } else {
            $('body').addClass('modalOpen')
        }
    }

    const toggleAccountMenu = () => {
        $('.accountMenu').toggleClass('active')
    }

    $('html').click(event => {
        if($(event.target).parents('#accountButton').length === 0 && $(event.target).parents('.accountMenu').length === 0 && !$(event.target).is('.accountMenu')) {
            $('.accountMenu').removeClass('active')
        }
    })

    const getDataFromDB = searchString => {
        $.get('../helpers/searchPosts.php', {searchString} ,(res) => {
            $('.searchPanel-background .posts').html(res);
        })

        $.get('../helpers/searchUsers.php', {searchString} ,(res) => {
            $('.searchPanel-background .users').html(res);
        })
    }


    let searchDelay = {};


    const getWordFromString = (position, string) => {
        if (position >= 0) {
            while (position >= 0) {
                char = string.charAt(position)
                if (char == " ") break;
                position --;
            }
            position ++;
        }

        let word = "";

        while (position < string.length) {
            char = string.charAt(position)
            if (char == " ") break;
            word += char;
            position ++;
        }

        return word;
    }

    const getCaretIndex = element => {
        let position = 0;
        const isSupported = typeof window.getSelection !== "undefined";
        if (isSupported) {
            const selection = window.getSelection();
            if (selection.rangeCount !== 0) {
            const range = window.getSelection().getRangeAt(0);
            const preCaretRange = range.cloneRange();
            preCaretRange.selectNodeContents(element);
            preCaretRange.setEnd(range.endContainer, range.endOffset);
            position = preCaretRange.toString().length;
            }
        }
        return position;
    }

    //Zwraca znak przed wskaźnikiem
    function getCharacterPrecedingCaret(containerEl) {
        var precedingChar = "", sel, range, precedingRange;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.rangeCount > 0) {
                range = sel.getRangeAt(0).cloneRange();
                range.collapse(true);
                range.setStart(containerEl, 0);
                precedingChar = range.toString().slice(-1);
            }
        } else if ( (sel = document.selection) && sel.type != "Control") {
            range = sel.createRange();
            precedingRange = range.duplicate();
            precedingRange.moveToElementText(containerEl);
            precedingRange.setEndPoint("EndToStart", range);
            precedingChar = precedingRange.text.slice(-1);
        }
        return precedingChar;
    }

    const TAGS = ['php', 'react', 'java', 'c++', 'javascript', 'angular', 'vuejs', 'nodejs', 'python', 'ruby']
    let tempTag = "";



    $('#searchString').on('input', (e)=> {
        const searchString = e.target.value;
    

        if (searchString.length > 1) {
            clearTimeout(searchDelay);
            searchDelay = setTimeout(()=> getDataFromDB(searchString), 250)
        } else {
            $('.searchPanel-background .users').html('');
            $('.searchPanel-background .posts').html('');
        }
        

    });

</script>