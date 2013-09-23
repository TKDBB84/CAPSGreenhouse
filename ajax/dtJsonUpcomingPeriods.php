<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jeffrey
 * Date: 8/13/13
 * Time: 10:53 AM
 * To change this template use File | Settings | File Templates.
 */

    require_once __DIR__.'/../classes/DBconnection.php';
    $pdo_dbh = DBconnection::getFactory()->getConnection();

    $success = false;
    if( ( isset($_POST['date_to']) && isset($_POST['date_from']) ) || true ){

        //search chambers

        $results = array();

        $results[0]['chamber_id'] = 3;
        $results[0]['chamber_name'] = 'Chamber_3';
        $results[0]['period_id'] = 3;
        $results[0]['temp'] = "23&deg;C";
        $results[0]['min_daylight'] = 633;
        $results[0]['humidity'] = "63%";
        $results[0]['final_plant_date'] = date('F jS, Y',1375243200);
        $results[0]['final_harvest_date'] = date('F jS, Y',1375243200);

        $results[1]['chamber_id'] = 4;
        $results[1]['chamber_name'] = 'Chamber_4';
        $results[1]['period_id'] = 4;
        $results[1]['temp'] = "30&deg;C";
        $results[1]['min_daylight'] = 844;
        $results[1]['humidity'] = "84%";
        $results[1]['final_plant_date'] = date('F jS, Y',1375243200);
        $results[1]['final_harvest_date'] = date('F jS, Y',1375243200);

        $results[2]['chamber_id'] = 2;
        $results[2]['chamber_name'] = 'Chamber_2';
        $results[2]['period_id'] = 2;
        $results[2]['temp'] = "22&deg;C";
        $results[2]['min_daylight'] = 422;
        $results[2]['humidity'] = "42%";
        $results[2]['final_plant_date'] = date('F jS, Y',1375243200);
        $results[2]['final_harvest_date'] = date('F jS, Y',1375243200);


        $success = true;
        $json_ret = '{ "iTotalRecords" : '.count($results).', '.
                    '  "iTotalDisplayRecords" : '.count($results).', '.
                    '  "sEcho" : "'.$_POST['sEcho'].'", '.
        $json_ret .= ' "aaData" : [ ';
        foreach($results as $row){
            $json_ret .= '{ '.
                         '"chamber_id" : '.$row['chamber_id'].', '.
                         '"Chamber" : "'.$row['chamber_name'].'", '.
                         '"period_id" : '.$row['period_id'].', '.
                         '"Temp" : "'.$row['temp'].'", '.
                         '"Daylight" : '.$row['min_daylight'].', '.
                         '"Humidity" : "'.$row['humidity'].'", '.
                         '"Final Plant Date" : "'.$row['final_plant_date'].'", '.
                         '"Final Harvest Date" : "'.$row['final_harvest_date'].'"'.
                        '},';
        }
        $json_ret = rtrim($json_ret,',');
        $json_ret .= ']} ';
    }

    if($success === false){
        die('{}');
    }elseif($success === true){
        header('Content-type: application/json; charset=utf-8');
        die($json_ret);
    }else{
        die('{ "ERROR" : "'.$success.'"');
    }

?>
