<?php
include_once 'header.php';
include_once './classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include './headIncludes.php'; ?>
    </head>
    <body>

        <div class="page-header">
            <img src="./img/rightmire.png" alt="rightmire" class=".img-responsive" />
            <h1 style="display: inline-block">GreenHouse Support Facility</h1>
        </div>
        <?php include_once 'nav.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="right-main-area">
                        <h2>Upcoming Settings</h2>
                        <div id='dates'>
                            From: <input type='text' id='datepicker1' /> -
                            To: <input type='text' id='datepicker2' />
                        </div>
                        <div class="top-buffer">
                            <table id="dtUpcomingPeriods">
                                <thead>
                                    <th>Chamber</th>
                                    <th>Temp</th>
                                    <th>Daylight</th>
                                    <th>Humidity</th>
                                    <th>Final Plant Date</th>
                                    <th>Final Harvest Date</th>
                                </thead>
                                <tbody>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include './bodyIncludes.php'; ?>
        <script>
            $(document).ready(function(){


                $("#datepicker1").datepicker({

                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onClose: function( selectedDate ) {
                        $( "#datepicker2" ).datepicker( "option", "minDate", selectedDate );
                    },

                    onSelect : function() {
                        var to_date = $('#datepicker2').datepicker( "getDate" );
                        var from_date = $('#datepicker1').datepicker( "getDate" );
                        if(to_date !== null && from_date !== null){
                            $.post('./ajax/dtJsonUpcomingPeriods.php',{'to_date':(to_date.getTime()/1000) , 'from_date':(from_date.getTime()/1000) },
                                function(response){

                                }
                                ,'html');
                        }
                    }
                });


                $("#datepicker2").datepicker({
                    defaultDate: "+3m",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onClose: function( selectedDate ) {
                        $( "#datepicker1" ).datepicker( "option", "maxDate", selectedDate );
                    },
                    onSelect : function() {
                        var to_date = $('#datepicker2').datepicker( "getDate" );
                        var from_date = $('#datepicker1').datepicker( "getDate" );
                        if(to_date !== null && from_date !== null){
                            $.post('./ajax/dtJsonUpcomingPeriods.php',{'to_date':(to_date.getTime()/1000) , 'from_date':(from_date.getTime()/1000) },
                                function(response){

                                }
                                ,'json');
                        }
                    }
                });


                var $dtUpcomingPeriods = $('#dtUpcomingPeriods').dataTable( {
                    "aoColumns": [
                        { "mData": "Chamber" },
                        { "mData": "Temp" },
                        { "mData": "Daylight" },
                        { "mData": "Humidity" },
                        { "mData": "Final Plant Date" },
                        { "mData": "Final Harvest Date" },
                        { "mData": "chamber_id" , "bVisible": false },
                        { "mData": "period_id" , "bVisible": false }
                    ],
                    "sAjaxSource": './ajax/dtJsonUpcomingPeriods.php',
                    "bJQueryUI": true,
                    "sServerMethod": "POST"
                } );

            });
        </script>
    </body>
</html>