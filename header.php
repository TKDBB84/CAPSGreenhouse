<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    include_once 'ticket_connection.php';
    $pdo_dbh_ticket = new PDO("mysql:host=$DBAddress;port=3306;dbname=$ticketDBName", $DBUsername, $DBPassword);
    $sql_user_info = $pdo_dbh_ticket->prepare("SELECT `email`,`lab_id`,isadmin` FROM `users` WHERE `user_id` = :user_id");
    $sql_isMatn = $pdo_dbh_ticket->prepare('SELECT 1 FROM matnc_staff WHERE user_id = :user_id');
    $sql_update_security = $pdo_dbh_ticket->prepare('UPDATE users SET `last_ip` = :last_ip, `last_crypt` = :unID WHERE user_id = :user_id');
    $sql_log_user = $pdo_dbh_ticket->prepare('INSERT INTO user_log (fk_user_id) VALUES (:user_id)');
    $sql_isCaps = $pdo_dbh_ticket->prepare('SELECT 1 FROM `labs` where `lab_id` = :lab_id and `is_caps` = 1');

if (!isset($_SESSION['gh_user_id'])) {
    if (isset($_COOKIE)) {
        include_once 'chkcookie.php';
        if (validCookie($_COOKIE)) {
            $_SESSION['gh_user_id'] = $_COOKIE['gh_user_id'];
            $user_id = $_COOKIE['gh_user_id'];
            $sql_user_info->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $sql_user_info->execute();
            $row = $sql_user_info->fetch(PDO::FETCH_ASSOC);
            if (!$row) die('Unrecoverable Cookie Error,user_id not found, Please Contact Webmaster');
            $_SESSION['gh_lab_id'] = $row['lab_id'];
            $_SESSION['gh_email'] = $row['email'];
            $_SESSION['gh_prof_edit'] = ($row['isadmin'] == 5) ? true : false;
            $_SESSION['gh_isadmin'] = ($row['isadmin'] == 2) ? true : false;
            $_SESSION['gh_isfaclt'] = ($row['isadmin'] == 1) ? true : false;
            $sql_isMatn->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $sql_isMatn->execute();
            $row = $sql_isMatn->fetchAll();
            $_SESSION['gh_isMatn'] = (count($row) == 1);

            $sql_isCaps->bindValue(':lab_id', $_SESSION['gh_lab_id']);
            $sql_isCaps->execute();
            $row = $sql_isCaps->fetch(PDO::FETCH_ASSOC);
            $_SESSION['gh_isCAPS'] = (count($row) == 1);
            $last_ip = $_SERVER['REMOTE_ADDR'];
            $unID = uniqid('', true);
            $sql_update_security->bindValue(":last_ip", $last_ip, PDO::PARAM_STR);
            $sql_update_security->bindValue(":unID", $unID, PDO::PARAM_STR);
            $sql_update_security->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $sql_update_security->execute();
            if (isset($_COOKIE['gh_creation']))
                setcookie("last_crypt", $unID, $_COOKIE['gh_creation'] + 3600 * 24 * 30, '/greenhouse');
            $sql_log_user->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $sql_log_user->execute();

            $isadmin = $_SESSION['gh_isadmin'];
            $islabmanager = $_SESSION['gh_islabmanager'];
            $islabowner = $_SESSION['gh_islabowner'];
        }
    }
}
$user_id = $_SESSION['gh_user_id'];
?>