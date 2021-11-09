<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEV 4 ALL</title>
    <link rel="icon" type="image/png" href="./favicon.png"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/snackbar.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script src="./js/avataaars.js"></script>
    <script src="./js/snackbar.js"></script>
    <script src="./js/compressor.js"></script>

    <script>
        if (Cookies.get('darkMode') == 'true') {
            console.log('test')
            $('html').addClass('darkMode');
        }

        if (window.matchMedia('(prefers-color-scheme: dark)').matches && Cookies.get('darkMode') == undefined) {
            console.log('test')
            $('html').addClass('darkMode');
        }

    </script>
</head>
<body>
    <?php 
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        //$_IPADDR = 'http://localhost:3000';
        $_IPADDR = "https://dev4all.herokuapp.com/";

        echo("<script>
            const _IPADDR = '" .$_IPADDR ."'
        </script>");

        session_start();

        include('./config/db_connection.php');
        $conn = OpenCon();

        if (isset($_SESSION['userID'])) {
            $result = $conn->query("SELECT banned FROM users WHERE users.user_id = {$_SESSION['userID']}");
            if ($result)
                if ($row = $result->fetch_array())
                    if ($row['banned'] == 1) {
                        session_destroy();
                        session_unset();
                        $_SESSION = NULL;
                    }
        }

        include('./components/header.php');

        $request = '';

        if (isset($_SERVER['PATH_INFO'])) {
            $request = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_URL'])) {
            $request = $_SERVER['REDIRECT_URL'];
        } else {
            $request = "/";
        }
        
        //echo (json_encode($_SERVER));

        switch ($request) {
            case '/' :
                include('./components/postsContainer.php');
                break;
            case '/index.php' :
                header("Location: $_IPADDR");
                break;
            case '/login':
                include('./components/login.php');
                break;
            case '/register':
                include('./components/register.php');
                break;
            case '/post':
                include('./components/post.php');
                break;
            case '/user':
                include('./components/user.php');
                break;
            case '/addPost':
                include('./components/addPost.php');
                break;
            case '/adminPanel': 
                if($_SESSION['admin'] != 1) echo "<h1>Brak dostępu</h1>";
                else include('./components/admin.php');
                break;
            case '/account':
                if(!isset($_SESSION['userID'])) echo "<script>document.location.href = '$_IPADDR/login'</script";
                else include('./components/account.php');
                break;
            case '/logout':
                setcookie(session_name(), '', 100);
                session_unset();
                session_destroy();
                $_SESSION = array();
                echo "<script>document.location.href = '$_IPADDR'</script>";
                break;
            default:
                break;
        }

        include('./components/footer.php');
    ?>

    <div class="snackbarsContainer"></div>


    <script src="/js/scripts.js"></script>
    <script>
        if (!Cookies.get('cookies_accepted')) {
            $('body').append('<div id="acceptCookies"><button onclick="acceptCookies()"><span class="material-icons">close</span></button><h3>Ta strona wykorzystuje pliki cookie</h3>Używamy informacji zapisanych za pomocą plików cookies w celu zapewnienia maksymalnej wygody w korzystaniu z naszego serwisu. Jeżeli wyrażasz zgodę na zapisywanie informacji zawartej w cookies kliknij na „x” w prawym górnym rogu tej informacji. Jeśli nie wyrażasz zgody, ustawienia dotyczące plików cookies możesz zmienić w swojej przeglądarce.</div>')
        }

        const acceptCookies = () => {
            Cookies.set('cookies_accepted', true, {expires: 30000})
            $('#acceptCookies').remove()
        }

        const deleteAnimationOnStart = $('head').append("<style>.postCard-picture-sideButtons-button--like.liked::before, .postCard-picture-sideButtons-button--bookmark.bookmarked::before {visibility: hidden;}</style>")
        setTimeout(()=>{
            $('head').append("<style>.postCard-picture-sideButtons-button--like.liked::before, .postCard-picture-sideButtons-button--bookmark.bookmarked::before {visibility: visible;}</style>")
        }, 1000)
    </script>
</body>
</html>