<?php
    if (isset($_GET['id'])) {
        $result = $conn->query("SELECT * FROM users WHERE `user_id` = {$_GET['id']}");
        if ($result) {
            $user = $result->fetch_assoc();
            if ($user) {
                $avatarSVG = $user['avatar_svg'];
                $fullName = $user['full_name'];
                $userID = $user['user_id'];
                $joinDate = date("d.m.Y", $user['created_at']);
?>
                <link rel="stylesheet" href="/css/user.css">

                <div class="userPage">
                    <div class="user">
                        <div class='avatar'><?php echo $avatarSVG?></div>
                        <div class='fullName'><?php echo $fullName?></div>
                        <div class='joinDate'>Dołączył(a): <?php echo $joinDate?></div>
                        <div class='buttons'>
                        <?php if (isset($_SESSION['userID'])){?>
                            <button onclick="sendMessageDialog()">Napisz wiadomość</button>
                            <button class='disabled'>Obserwuj</button>
                            <button onclick="reportUserDialog()">Zgłoś</button>
                        <?php } ?>
                            <button onclick="showPosts(<?php echo $userID ?>)">Pokaż posty</button>
                        </div>
                    </div>
                </div>

                <script>

                    const showPosts = id => {
                        document.location = "<?php echo($_IPADDR)?>?userID=" + id;
                    }

                    const reportUser = id => {
                        const reason = $('.reason textarea').val();
                        $.get("../helpers/reportUser.php", {id, reason}, res => {
                            if (res == 'success') {
                                Snackbar.show({ text: 'Wysłano zgłoszenie', customClass: 'snackbar' });
                                closeModal();
                            } else {
                                Snackbar.show({ text: res, customClass: 'snackbar' })
                            }
                        })
                    }

                    const reportUserDialog = () => {
                        const userID = <?php echo $userID?>;
                        $('body').append(`
                            <div class='modalBackground active'>
                                <div class="modalWindow report-user">
                                    <button type="button" onclick='closeModal()' class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                                    <div class='modalWindow-title'>Zgłoś post</div>
                                    <div class='reason'>
                                        <textarea placeholder='Powód zgłoszenia...'></textarea>
                                    </div>
                                    <div class='report-buttons'>
                                        <button id='report' onclick='reportUser(${userID})'>Zgłoś</button>
                                        <button onclick='closeModal()'>Anuluj</button>
                                    </div>
                                </div>
                            </div>`)
                    }

                    
                    const sendMessage = (msgTo) => {
                        const msgBody = $('.message textarea').val();
                        $.post("../helpers/account/sendMessage.php", {msgTo, msgBody}, res => {
                            if (res == 'success') {
                                Snackbar.show({ text: 'Wysłano wiadomość', customClass: 'snackbar' });
                                closeModal();
                            } else {
                                Snackbar.show({ text: res, customClass: 'snackbar' })
                            }
                        })
                    }

                    const sendMessageDialog = () => {
                        const userID = <?php echo $userID?>;
                        $('body').append(`
                            <div class='modalBackground active'>
                                <div class="modalWindow send-message">
                                    <button type="button" onclick='closeModal()' class="modalWindow-closeButton"><span class='material-icons'>close</span></button>
                                    <div class='modalWindow-title'>Napisz wiadomość</div>
                                    <div class='message'>
                                        <textarea placeholder='Treść wiadomości...'></textarea>
                                    </div>
                                    <div class='info'>Dalsza część rozmowy będzie widoczna na twoim profilu w zakładce "Wiadomości"</div>
                                    <div class='message-buttons'>
                                        <button id='message' onclick='sendMessage(${userID})'>Wyślij</button>
                                        <button onclick='closeModal()'>Anuluj</button>
                                    </div>
                                </div>
                            </div>`)
                    }
                </script>

<?php       }
        }
    } 
?>