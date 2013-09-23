<?php

require_once __DIR__.'../classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();
if(isset($_POST['email'])){
    $username = strtolower($_POST['email']);
    if(filter_var($username, FILTER_VALIDATE_EMAIL)){
        if($username != ''){
            $stmt_chk_username = $pdo_dbh->prepare('SELECT 1 FROM `Users` WHERE `email` = :username');
            $stmt_chk_username->bindValue(':username', $username, PDO::PARAM_STR);
            if($stmt_chk_username->execute()){
                $result = $stmt_chk_username->fetchAll(PDO::FETCH_ASSOC);
                if(empty($result)){
                    die('1');
                } die('Email Already In Use');
            } die('Error Querying To Database, Please Contact <a href="mailto:campbell.1333@osu.edu">Webmaster</a>');
        } die("Must Supply Email Address");
    } die('Email Address Appears Invalid');
} die("Must Supply Email Address");

?>