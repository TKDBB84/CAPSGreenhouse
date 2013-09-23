<?php

    include_once __DIR__.'/../classes/DBconnection.php';
    include_once __DIR__.'/../classes/Chamber.php';

    $pdo_dbh = DBconnection::getFactory()->getConnection();

    $allChambers = Chamber::getAllChambers();


    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');

    $json_return["iTotalRecords"] = count($allChambers);
    $json_return["iTotalDisplayRecords"] = count($allChambers);
    $json_return["sEcho"] = $_POST['sEcho'];
    $json_return['aaData'] = $allChambers;

    echo json_encode($json_return);


?>
