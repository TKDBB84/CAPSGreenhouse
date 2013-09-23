<?php
    ob_start();
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(!isset($_SESSION['gh_user_id'])){
        if(!isset($_COOKIE['gh_user_id'])){
            ob_end_clean();
            header('Refresh: 5; URL=http://128.146.132.54/greenhouse/index.php');
            echo "<div style='margin-top: 50px;'>You Are Either Not Logged In, Or Your Session Has Expired,<br/>
                  Redirecting You To The Home Page In 5 Seconds, or you may
                  <a href='http://128.146.132.54/greenhouse/index.php'>Click Here</a></div>";
        }else{
            $_SESSION['gh_user_id'] = $_COOKIE['gh_user_id'];
        }
    }
    ob_end_clean();
