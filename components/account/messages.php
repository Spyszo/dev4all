<link rel="stylesheet" href="/css/account/messages.css">

<?php 

$result = $conn->query("SELECT DISTINCT `user_id`, `avatar_svg`, `full_name` FROM messages JOIN users ON users.user_id = messages.msg_to WHERE msg_from = {$_SESSION['userID']} UNION SELECT DISTINCT `user_id`, `avatar_svg`, `full_name` FROM messages JOIN users ON users.user_id = messages.msg_from WHERE msg_to = {$_SESSION['userID']}");
if ($result) {
    if ($result->num_rows > 0) { ?>

        <div class='contacts'>
            <ul>
                <?php 
                    while ($user = $result->fetch_assoc()) {
                        echo "<li onclick='showMessages(this, {$user['user_id']})'>{$user['avatar_svg']} <span>{$user['full_name']}</span></li>";
                    }
                ?>
            </ul>
        </div>

        <div class='messages-container'>
            <div class='messages'></div>
            <div class='reply'></div>
        </div>

    <?php } else {
        echo "<h2>Brak wiadomości</h2>";
    }
}
?>


<script>
    let currentUser = 0;

    const showMessages = (e, fromID) => {
        $('ul li').removeClass('active');
        $(e).addClass('active');

        if ($('.contacts ul li.active span').html() == "Administracja") {
            $('.messages-container .reply').html(`
                <p style='text-align: center; width: 100%'>Nie można odpowiedzieć</p>
            `)
        } else {
            $('.messages-container .reply').html(`
                <form id="message-reply-form">
                    <input type="text">
                    <button><span class='material-icons'>send</span></button>
                </form>
            `)
        }

        currentUser = fromID;
        $.get('/helpers/account/getMessages.php', {fromID}, res => {
            $('.messages-container .messages').html(res)
        })
    }

    $(".reply input").on('keyup', (e) => {
        if (e.key === 'Enter' || e.keyCode === 13) {
            sendMessage()
        }
    })

    if ($('.contacts ul li').length > 0) {
        $('.contacts ul li').first().trigger('click')
    }

    $("#message-reply-form").on("submit", e=>{
        e.preventDefault()
        const msgBody = $('.reply input').val();
        const msgTo = currentUser;
        if (msgBody.length > 0) {
            $.post("/helpers/account/sendMessage.php", {msgTo, msgBody}, res => {
                if (res == 'success') {
                    $('.messages-container .messages').append(`
                        <div class='message message-outgoing'>
                            <div class='body'>${msgBody}</div>
                            <div class='date'>Przed chwilą</div>
                        </div>   
                    `)
                    $('div.messages').scrollTop( $('div.messages')[0].scrollHeight );
                }
            })
        }
    })

</script>