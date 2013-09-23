<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if (isset($_COOKIE['gh_creation']))
    header('Location: ./chkuser.php');

$bad_message = '';
if (isset($_REQUEST['bad'])) {
    if ($_REQUEST['bad'] == 'cookie') {
        $bad_message = 'Your `Remember Me` Has Expired You Can Renew It By Logging In Again.';
    } else {
        $bad_message = 'Invalid Password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>BioTech Support System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel='stylesheet'
          href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/<?php echo $google_theme; ?>/jquery-ui.min.css'
          type='text/css'/>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./css/gh_start.css" type="text/css"/>
</head>
<body id='body'>
<div class="login_title">
    <span>Welcome To The BioTech Support System</span>
</div>
<div id="login_container">
    <form action="chkuser.php" id="form_login" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <div id="lower">
            <input type="checkbox"><label for="checkbox">Keep me logged in</label>
            <input type="submit" value="Login">
        </div>
        <!--/ lower-->
    </form>
    <p><a href="./register.php">Register</a><br/><a href='./forgot.php'>Forgot Password?</a></p>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
<script>
    <?php
    if(!isset($_GET['ie']) || $_GET['ie'] != 'ok')
     echo '$(document).ready(function(){
            if ( $.browser.msie ){
                $("#body").html("<div style=\'width:100%; height:100%;\'>\n\
                                <div class=\'ie_error\' style=\'position: absolute; width: 30%; height: 50px; top: 50%; left: 30%; margin-left: -50px; margin-top: -10%;\'>\n\
                                 <img src=\'./img/sad-ie.png\' /> \n\
                                 <p>You Appear To Be Using Internet Explorer!\n\
                                 Internet Explorer Is Not Supported By This Service   \n\
                                 Please Download Either <a href=\'https://www.google.com/intl/en/chrome/browser/\'>Google Chrome</a>\n\
                                 Or <a href=\'http://www.mozilla.org/en-US/firefox/new/\'>Mozilla Firefox</a> \n\
                                 and try again. </p>\n\
                                 <p>To Continue To This Service Anyway <a href=\'./index.php?ie=ok\'>Click Here</a></p>\n\
                                 <br /><br /><br /><p><span style=\'font-size: .3em;\'>To Be Fair, You Probably Shouldn\'t Ever Use IE.</span></p></div></div>");
                                 
              }
            });';
    ?>
</script>
</body>
</html>