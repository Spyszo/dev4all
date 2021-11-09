<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/admin/reports.css">
<link rel="stylesheet" href="../css/admin/users.css">

<div class="admin-panel">
    <div class="nav">
        <div class="title">
            <div class="admin-icon"><span class='material-icons'>admin_panel_settings</span></div>
            <p>Panel <br> administracyjny</p>
        </div>
        <ul>
            <li id="postsToAccept" selected><span class='material-icons'>inventory</span>Posty do akceptacji</li>
            <li id="reports"><span class='material-icons'>report</span>Zgłoszenia</li>
            <li id="users"><span class='material-icons'>manage_accounts</span>Użytkownicy</li>
        </ul>
    </div>

    <div class="content">

    </div>
</div>

<?php include('admin/postsToAccept.php')?>
<?php include('admin/reports.php')?>
<?php include('admin/users.php')?>

<script>
    getPostsToAccept()

    $('ul li').click(e => {
        $('.content').html("<div class='loader'></div>")
        $('ul li').removeAttr('selected');
        $(e.target).attr('selected', true)
        switch (e.currentTarget.id) {
            case 'postsToAccept':
                getPostsToAccept()
                break;
            case 'reports':
                getReports()
                break;
            case 'users':
                getUsers()
                break;
        }
    })

    $('body').on('resize', () => {

    })
</script>