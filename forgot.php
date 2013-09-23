<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_start();
?>
<!DOCTYPE html>
<head>
    <title>BioTech Support System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel='stylesheet'
          href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/<?php echo $google_theme; ?>/jquery-ui.min.css'
          type='text/css'/>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./css/gh_start.css" type="text/css"/>
</head>
<body>
<div class="forgot_outer_div" style="">
    <div class="forgot_inner_div" style="">
        <span class="forgot_inner_text">BioTech Support System </span> <br/>
        <span class="forgot_inner_text">Reset Password</span>
    </div>
</div>
<div id="login_container">
    <form action="chkuser.php" id="form_login" method="POST">
        <label for="username">Email Address:</label>
        <input type="text" id="username" name="username">

        <div id="lower">
            <input type="submit" value="Submit">
        </div>
        <!--/ lower-->
    </form>
    <p><a href="./index.php">Return To Login</a><br/>
</div>
<?php include './bodyIncludes.php' ?>
</body>