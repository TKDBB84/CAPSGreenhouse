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
                    <button id="addChamber" class="btn btn-primary pull-right">Add New Chamber</button>
                </div>
            </div>
        </div>
        <div id="mkChamber">
            <fieldset id="fs_dates">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Name:</label>
                        <input type="text" name="chamber_name" class="setting form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Total Space:
                            <div class="input-group">
                                <input type="number" name="chamber_space" class="setting form-control"/>
                                <span class="input-group-addon">trays</span>
                            </div>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php include __DIR__.'/../bodyIncludes.php'; ?>
        <script>
            $(document).ready(function(){

                var $dialog = $('#mkChamber').dialog({
                    autoOpen: false,
                    height: 255,
                    width: 290,
                    modal: true,
                    title: 'Create Settings',
                    buttons: {
                        "Add" : function(){
                            alert("SAVED!");
                            $(this).dialog("close");
                        },
                        cancel: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function(){
                        $('.setting').val('');
                    }
                });

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

                $('#addChamber').click(function(){
                   $dialog.dialog("open");
                });
            });
        </script>
    </body>
</html>