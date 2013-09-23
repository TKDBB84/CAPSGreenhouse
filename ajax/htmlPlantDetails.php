<?php
    require_once __DIR__.'/../classes/DBconnection.php';
    $pdo_dbh = DBconnection::getFactory()->getConnection();

    if(isset($_POST['plant_id'])){

        $stmt_plant_details = $pdo_dbh->prepare('SELECT * FROM `Plants` WHERE `set_id` = :id');
        $stmt_plant_details->bindValue(':id',$_POST['plant_id'],PDO::PARAM_INT);

        if(true){ //$stmt_plant_details->execute()){

            //$plant = $stmt_plant_details->fetch(PDO::FETCH_ASSOC);
            $plant['ideal_temp'] = rand(50,80);
            $plant['ideal_daylight'] = rand(400,900);
            $plant['ideal_humidity'] = rand(15,80);

            if(!empty($plant)){
                echo 'Ideal Temp: ',$plant['ideal_temp'],'&deg;C<br />',
                     'Ideal Daylight: ',$plant['ideal_daylight'],' min<br />',
                     'Ideal Humidity: ',$plant['ideal_humidity'],'%';
            }else die('ERROR: Could Not Find Plant ID');
        }else die('ERROR: Cannot Contact Database');
    }else die('ERROR: No Plant ID Provided');



?>