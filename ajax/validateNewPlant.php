<?php

require_once __DIR__.'../classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();

$json_result = '{';


if(!isset( $_POST['ideal_temp'] ) || trim( $_POST['ideal_temp'] ) == ''){
  $json_result .= '"ideal_temp" : "ok",';
}else if ( !(filter_var($_POST['ideal_temp'], FILTER_VALIDATE_INT)) ) {
  $json_result .= '"ideal_temp" : "error",';
}else{
    $json_result .= '"ideal_temp" : "ok",';
}

if(!isset( $_POST['ideal_daylight'] ) || trim( $_POST['ideal_daylight'] ) == ''){
  $json_result .= '"ideal_daylight" : "ok",';
}else if ( !(filter_var($_POST['ideal_daylight'], FILTER_VALIDATE_INT)) ) {
  $json_result .= '"ideal_daylight" : "error",';
}else{
    $json_result .= '"ideal_daylight" : "ok",';
}

if(!isset( $_POST['num_trays'] ) || trim( $_POST['num_trays'] ) == ''){
  $json_result .= '"num_trays" : "ok"';
}else if ( !(filter_var($_POST['num_trays'], FILTER_VALIDATE_INT)) ) {
  $json_result .= '"num_trays" : "error"';
}else{
    $json_result .= '"num_trays" : "ok"';
}

$json_result .= '}';
die($json_result);

?>