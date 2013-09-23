<?php
include_once 'header.php';
include_once './classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>BioTech Support System</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="./js/jquery.qtip.min.js"></script>
        <link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/<?php echo $google_theme; ?>/jquery-ui.min.css' type='text/css'/>
        <link rel="stylesheet" href="./css/jquery.qtip.min.css" type="text/css" />
        <link rel="stylesheet" href="./css/gh_main.css" type="text/css" />
    </head>
    <body>
        <div style="display: block; width: 90%; margin-left: 5%; margin-right: 5%; margin-top: 0px; height: 100px;">
            <img src="./images/rightmire2.JPG" alt="rightmire" style="height: 100px; margin-right: 30px;" />
            <span style="position: relative; top: -50%; font-size: 1.5em; margin-left: auto; margin-right: auto;">GreenHouse Support Facility</span>
        </div>
        <hr/>
        <div id="wrapper" style="width: 100%; height: 100%; margin-bottom: 50px;">
            <div id="left-nav" style="position: relative; width: 200px; float: left; margin-left: 0px; height: 100%;">
                <nav>
                <?php include_once 'nav.php'; ?>
                </nav>
            </div>
        <div id="right-main-area" style="margin-left: 250px;">
            <h2>Upcoming Settings</h2>
            <div id='dates'>
                From: <input type='text' id='datepicker1' /> -
                To: <input type='text' id='datepicker2' />
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function(){
            $("#datepicker1").datepicker();
            $("#datepicker2").datepicker();
        });
    </script>
</html>