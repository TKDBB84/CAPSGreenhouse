<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    /*if (!isset($_SESSION['gh_user_id'])){
        header()//prompt to login
    }*/
    $_SESSION['gh_user_id'] = 1;

    require_once __DIR__.'/../classes/DBconnection.php';
    $pdo_dbh = DBconnection::getFactory()->getConnection();

    $user_id = $_SESSION['gh_user_id'];
                                                //need to limit fields to necessary data
    $stmt_get_reservations = $pdo_dbh->prepare('SELECT * FROM `Plants` AS p
                                                JOIN `Periods` AS per
                                                ON p.`fk_period_id` = per.`period_id`
                                                JOIN `Chambers` AS c
                                                ON per.`fk_chamber_id` = c.`chamber_id`
                                                WHERE p.`fk_user_id` = :user_id
                                                AND per.`final_harvest_date <= DATE(NOW())');


    $stmt_get_reservations->bindValue(':user_id',$user_id,PDO::PARAM_INT);
    $stmt_get_reservations->execute();
    $results = $stmt_get_reservations->fetchAll(PDO::FETCH_ASSOC);

    $results = array();

    $results[0]['plant_id'] = 3;
    $results[0]['plant_name'] = 'my_plant_3';
    $results[0]['chamber_id'] = 3;
    $results[0]['chamber_name'] = 'Chamber_3';
    $results[0]['period_id'] = 3;
    $results[0]['temp'] = 63;
    $results[0]['min_daylight'] = 633;
    $results[0]['humidity'] = 63;
    $results[0]['final_plant_date'] = 1375243200;
    $results[0]['final_harvest_date'] = 1375243200;


    $results[1]['plant_id'] = 4;
    $results[1]['plant_name'] = 'my_plant_4';
    $results[1]['chamber_id'] = 4;
    $results[1]['chamber_name'] = 'Chamber_4';
    $results[1]['period_id'] = 4;
    $results[1]['temp'] = 84;
    $results[1]['min_daylight'] = 844;
    $results[1]['humidity'] = 84;
    $results[1]['final_plant_date'] = 1375243200;
    $results[1]['final_harvest_date'] = 1375243200;


    $results[2]['plant_id'] = 2;
    $results[2]['plant_name'] = 'my_plant_2';
    $results[2]['chamber_id'] = 2;
    $results[2]['chamber_name'] = 'Chamber_2';
    $results[2]['period_id'] = 2;
    $results[2]['temp'] = 42;
    $results[2]['min_daylight'] = 422;
    $results[2]['humidity'] = 42;
    $results[2]['final_plant_date'] = 1375243200;
    $results[2]['final_harvest_date'] = 1375243200;

    $json_string = '{ ';
    if(count($results) > 0){
        $json_string .= '"reservations" : [';
        foreach($results as $row){
            $json_string .= ' { '.
                              '"plant_id" : '.$row['plant_id'].', '.
                              '"plant_name" : "'.$row['plant_name'].'", '.
                              '"chamber_id" : '.$row['chamber_id'].', '.
                              '"chamber_name" : "'.$row['chamber_name'].'", '.
                              '"period_id" : '.$row['period_id'].', '.
                              '"temp" : '.$row['temp'].', '.
                              '"min_daylight" : '.$row['min_daylight'].', '.
                              '"humidity" : '.$row['humidity'].', '.
                              '"final_plant_date" : '.$row['final_plant_date'].', '.
                              '"final_harvest_date" : '.$row['final_harvest_date'].
                            '},';
        }
        $json_string = rtrim($json_string,',');
        $json_string .= '] ';
    }
    $json_string .= '}';

    header('Content-type: application/json; charset=utf-8');
    die($json_string);



?>