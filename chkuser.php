<?php

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once 'ticket_connection.php';
$pdo_dbh_ticket = new PDO("mysql:host=$DBAddress;port=3306;dbname=$ticketDBName", $DBUsername, $DBPassword);
$sql_update_user_crypt = $pdo_dbh_ticket->prepare('UPDATE users SET `last_ip` = :last_ip, `last_crypt` = :unID WHERE user_id = :user_id');
$sql_is_matnc = $pdo_dbh_ticket->prepare('SELECT 1 FROM matnc_staff WHERE user_id = :user_id');
$sql_log_user = $pdo_dbh_ticket->prepare('INSERT INTO user_log (fk_user_id) VALUES (:user_id)');
$sql_isCaps = $pdo_dbh_ticket->prepare('SELECT 1 FROM `labs` where `lab_id` = :lab_id and `is_caps` = 1');


include_once 'chkcookie.php';
$bad_Cookie = false;
if (validCookie($_COOKIE)) {
    $_SESSION['gh_user_id'] = $_COOKIE['gh_user_id'];
    $sql_get_user_info = $pdo_dbh_ticket->prepare('SELECT `email`,`lab_id`,`isadmin` FROM users WHERE `user_id` = :user_id');
    $sql_get_user_info->bindValue(':user_id', $_SESSION['gh_user_id'], PDO::PARAM_INT);
    $sql_get_user_info->execute();
    $user_info = $sql_get_user_info->fetchAll(PDO::FETCH_ASSOC);
    if (count($user_info) <= 0)
        die('Unrecoverable Cookie Error,user_id not found, Please Contact Webmaster');
    $row = array_shift($user_info);
    $_SESSION['gh_lab_id'] = $row['lab_id'];
    $_SESSION['gh_email'] = $row['email'];
    $_SESSION['gh_prof_edit'] = ($row['isadmin'] == 5) ? true : false;
    $_SESSION['gh_isadmin'] = ($row['isadmin'] == 2) ? true : false;
    $_SESSION['gh_isfaclt'] = ($row['isadmin'] == 1) ? true : false;

    $sql_is_matnc->bindValue(":user_id", $_SESSION['gh_user_id'], PDO::PARAM_INT);
    $sql_is_matnc->execute();
    $is_matnc = $sql_is_matnc->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['gh_isMatn'] = (count($is_matnc) > 0);

    $sql_isCaps->bindValue(':lab_id', $_SESSION['gh_lab_id']);
    $sql_isCaps->execute();
    $is_caps = $sql_isCaps->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['gh_isCAPS'] = (count($is_caps) == 1);

    $unID = uniqid('', true);
    $sql_update_user_crypt->bindValue(":last_ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $sql_update_user_crypt->bindValue(':unID', $unID, PDO::PARAM_STR);
    $sql_update_user_crypt->bindValue(':user_id', $_SESSION['gh_user_id'], PDO::PARAM_INT);
    $sql_update_user_crypt->execute();
    $sql_log_user->bindValue(":user_id", $_SESSION['gh_user_id'], PDO::PARAM_INT);
    $sql_log_user->execute();
    if (isset($_COOKIE['gh_creation'])) {
        setcookie("last_crypt", $unID, $_COOKIE['gh_creation'] + 3600 * 24 * 30, '/greenhouse');
        if ($_SESSION['gh_isMatn'])
            header('Location: ./workview.php');
        else
            header('Location: ./viewtickets.php');
        die("1");
    } else {
        die('Unrecoverable Cookie Error,creation date does not exist, Please Contact Webmaster');
    }
} else {
    if (isset($_COOKIE)) {
        $cookie_keys = array_keys($_COOKIE);
        if (!empty($cookie_keys))
            foreach ($cookie_keys as $keyis)
                setcookie($keyis, '', time() - 3600,'/greenhouse');
    }
    $bad_Cookie = true;
}
if (isset($_POST['email']) && isset($_POST['pass'])) {
    $sql_get_user_info = $pdo_dbh_ticket->prepare('SELECT `user_id`,`email`,`lab_id`,`password`,`isadmin`,`approved` FROM users WHERE `email` = :email');
    $sql_get_user_info->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $sql_get_user_info->execute();
    $user_info = $sql_get_user_info->fetchAll(PDO::FETCH_ASSOC);

    if (count($user_info) <= 0) {
        header('Location: ./index.php?bad');
        die('3');
    }
    $row = array_shift($user_info);
    $supplied_pass = $_POST['pass'];
    $supplied_pass = hash_hmac("sha1", $supplied_pass, $this_sites_salt);
    $approved = ($row['approved'] == 1) ? true : false;
    if ($row['password'] == $supplied_pass && $approved) {
        $_SESSION['gh_user_id'] = $row['user_id'];
        $_SESSION['gh_lab_id'] = $row['lab_id'];
        $_SESSION['gh_email'] = $row['email'];
        $_SESSION['gh_prof_edit'] = ($row['isadmin'] == 5) ? true : false;
        $_SESSION['gh_isadmin'] = ($row['isadmin'] == 2) ? true : false;
        $_SESSION['gh_isfaclt'] = ($row['isadmin'] == 1) ? true : false;
        $sql_is_matnc->bindValue(":user_id", $_SESSION['gh_user_id'], PDO::PARAM_INT);
        $sql_is_matnc->execute();
        $is_matnc = $sql_is_matnc->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['gh_isMatn'] = (count($is_matnc) > 0);

        $sql_isCaps->bindValue(':lab_id', $_SESSION['gh_lab_id'], PDO::PARAM_INT);
        $sql_isCaps->execute();
        $is_caps = $sql_isCaps->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['gh_isCAPS'] = (count($is_caps) == 1);

        $unID = uniqid('', true);
        $sql_update_user_crypt->bindValue(":last_ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $sql_update_user_crypt->bindValue(':unID', $unID, PDO::PARAM_STR);
        $sql_update_user_crypt->bindValue(':user_id', $_SESSION['gh_user_id'], PDO::PARAM_INT);
        $sql_update_user_crypt->execute();
        $sql_log_user->bindValue(":user_id", $_SESSION['gh_user_id'], PDO::PARAM_INT);
        $sql_log_user->execute();
        if (isset($_POST['remember'])) {
            //setup cookie
            setcookie("user_id", $_SESSION['gh_user_id'], time() + 3600 * 24 * 30, '/greenhouse');
            setcookie("last_crypt", $unID, time() + 3600 * 24 * 30, '/greenhouse');
            setcookie("creation", time(), time() + 3600 * 24 * 30, '/greenhouse');
        }
        //success
        header('Location: ./viewtickets.php');
        die();
    } else {
        //fail
        header('Location: ./index.php?bad');
        die();
    }
} else {
    if ($bad_Cookie)
        header('Location: ./index.php?bad=cookie');
    else
        header('Location: ./index.php?bad');
    die();
}
die("Unrecoverable Login Error, Please Contact WebMaster");
?>
