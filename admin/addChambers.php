<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();





?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__.'/../headIncludes.php'; ?>
    </head>

    <body>
        <div class="page-header">
            <img src="../img/rightmire.png" alt="rightmire" class=".img-responsive" />
            <h1 style="display: inline-block">GreenHouse Support Facility</h1>
        </div>
        <?php include_once __DIR__.'/../nav.php'; ?>
        <div class="container">
            <div class="row">
                <div id="right-main-area" class="col-md-12">
                    <h2>All Chambers</h2>
                    <table id="dtAllChambers">
                        <thead>
                            <th>Chamber Name</th>
                            <th>Total Space (Trays)</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row top-buffer">
                <div class="col-md-12">
                    <button class="btn btn-primary pull-right">Add New Chamber</button>
                </div>
            </div>
        </div>
        <?php include __DIR__.'/../bodyIncludes.php'; ?>
        <script>
            $(document).ready(function(){
                var $dtAllChambers = $('#dtAllChambers').dataTable({
                    "aoColumns": [
                        { "mData": "name","sWidth": "30%" },
                        { "mData": "space" },
                        { "mData" : null , "bSortable":false,
                            "mRender" : function(data,type,row){
                                return '<a href="./addPeriods.php?chamber_id='+row.chamber_id+'">See Growth Peroids</a>';
                            }, "sWidth": "20%"
                        },
                        { "mData": "chamber_id", "bSortable": false,
                            "mRender" : function(data, type, row ){
                                return '<button class="btn btn-danger delChamber" data-id="'+data+'">Delete</button>';
                            }
                        }

                    ],
                    "sAjaxSource": '../ajax/dtJsonAllChambers.php',
                    "bJQueryUI": true,
                    "sServerMethod": "POST"
                });
            });
        </script>
    </body>
</html>