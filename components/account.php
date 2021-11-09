<link rel="stylesheet" href="../css/profile.css">
<?php 

if (isset($_SESSION['userID'])) {
    ?>
    
    <div class="profile">
        <div class="menu">
            <button class='minimize-button' onclick='minimizeMenu()'><span class="material-icons">menu_open</span></button>
            <div class="account-info">
                <div class='avatar'><?php echo $_SESSION['avatarSVG']?></div>
                <div class='name'><?php echo $_SESSION['firstName'] ."<br>" .$_SESSION['lastName']?></div>
            </div>
            <ul>
                <li id="account" class="active"><span class="material-icons">person</span>Konto</li>
                <li id="bookmarks"><span class="material-icons">bookmarks</span>Zakładki</li>
                <li id="charts" class="disabled"><span class="material-icons">query_stats</span>Statystyki</li>
                <li id="settings" class="disabled"><span class="material-icons">settings</span>Ustawienia</li>
                <li id="messages"><span class="material-icons">mail</span>Wiadomości</li>
            </ul>
        </div>
        <div class="content">
            <div class="closeContent"></div>

            <div class="item content-account">
                <?php include('account/profile.php'); ?>
            </div>

            <div class="item content-bookmarks" style="display:none">
                <?php include('account/bookmarks.php') ?>
            </div>

            <div class="content-messages" style="display:none">
                <?php include('account/messages.php'); ?>
            </div>
        </div>
    </div>

    <script>
        let prevItem = 'account';

        $('.menu ul li').click((e) => {
            const name = e.currentTarget.id;
            if ($(e.currentTarget).hasClass('disabled')) {
                return;
            }
            $('#' + prevItem).removeClass('active');
            $('#' + name).addClass('active');
            prevItem = name;
            {name == 'account'  ? $('.content-account').show()  : $('.content-account').hide()}
            {name == 'bookmarks'? $('.content-bookmarks').show(): $('.content-bookmarks').hide()}
            {name == 'messages' ? $('.content-messages').show() : $('.content-messages').hide()}

            $('.content').css("top", 0)

            index = $('#' + name).index()

            $('.indicator').css("top", 62 * index)
        })

        $('.closeContent').click((e)=>{
            $('.content').css("top", "100%")
        })

        const minimizeMenu = () => {
            $('.menu').toggleClass('minimized')
        }

    </script>
    
    <?php
}

?>