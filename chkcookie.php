<?php
function validCookie($cookie)
{
    //this function will validiate the cookie, and roll the last_crypt
    //if the user_id is valid; users who have had there cookie stolen,
    //or a logging in from a new IP will be force to re-loging
    $ret = false;
    if (isset($cookie)) {
        if (isset($cookie['user_id'])) {
            if (isset($cookie['last_crypt']) && isset($cookie['creation'])) {
                include_once 'ticket_connection.php';
                $pdo_dbh_ticket = new PDO("mysql:host=$DBAddress;port=3306;dbname=$ticketDBName", $DBUsername, $DBPassword);
                $sql_get_user_info = $pdo_dbh_ticket->prepare('SELECT last_ip,last_crypt,`approved` FROM users WHERE user_id = :user_id');
                $sql_get_user_info->bindValue(':user_id', $cookie['user_id'], PDO::PARAM_INT);
                $sql_get_user_info->execute();
                $user_info = $sql_get_user_info->fetchAll(PDO::FETCH_ASSOC);
                if (count($user_info) > 0) {
                    $row = array_shift($user_info);
                    $approved = ($row['approved'] == 1) ? true : false;
                    if ($row['last_ip'] == $_SERVER['REMOTE_ADDR'] && $row['last_crypt'] == $cookie['last_crypt'] && $approved)
                        $ret = true;
                    //$new_crypt = uniqid('',true);
                    /*$sql_update_user_crypt = $pdo_dbh_ticket->prepare('UPDATE users SET `last_crypt` = :new_crypt WHERE user_id = :user_id');
                    $sql_update_user_crypt->bindValue(':new_crypt',$new_crypt,PDO::PARAM_STR);
                    $sql_update_user_crypt->bindValue(":user_id",$cookie['user_id'],PDO::PARAM_INT);
                    $sql_update_user_crypt->execute();*/
                }
            }
        }
    }
    return $ret;
}

?>
