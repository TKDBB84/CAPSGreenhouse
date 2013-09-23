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
            <img src="img/rightmire.png" alt="rightmire" class=".img-responsive" />
            <h1 style="display: inline-block">GreenHouse Support Facility</h1>

    </div>
    <?php include_once 'nav.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Current Plants</h2>
                        <h3>Drag Plants To A Chamber To Reserve Space</h3>
                    </div>
                </div>
                <div id='dates' class="row top-buffer">
                    <div class="col-md-12">
                        Planting Dates From: <input type='text' id='datepicker1'/> -
                        To: <input type='text' id='datepicker2'/>
                    </div>
                </div>

                <div id='draggable_area' class="row top-buffer">
                    <div id='unreserved' class='unreserved chamber closed col-md-5' data-value='-1' style="margin-left: 25px;">
                        <span><h5>Not Reserved</h5></span><br/>
                        <div id="unreserved_plants">
                            <div data-value="4" class='plant'><div class="container"><span>My Plant 4</span></div></div>
                            <div data-value="3" class='plant'><div class="container"><span>My Plant 3</span></div></div>
                            <div data-value="2" class='plant'><div class="container"><span>My Plant 2</span></div></div>
                            <div data-value="1" class='plant'><div class="container"><span>My Plant 1</span></div></div>
                        </div>
                        <button id='create_plant' class="btn btn-primary btn-large btn-block">Add New Plant</button>
                    </div>
                    <div class="col-md-1" style="height: 590px;">&nbsp;</div>
                    <div id='all_chambers' style='' class="col-md-5">
                            Please Enter Dates To Search
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div style="display: none;" id="newPlantDiv">
        <form action="./mktrays.php" method="POST">
            <table>
                <tr>
                    <td>
                        <input type="text" class='newTrayInput' name="plant_name" id='plant_name' placeholder="Name Your Set Of Plant..."  style='width: 100%;' required/><br />
                        <em style="font-size: 0.75em;">This Is A Name Only For Your Reference, (ie. <span style="font-weight: bold;">2013-11-23 Treatment 1</span> )</em>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" class='newTrayInput' name="plant_species" id='plant_species' placeholder="Species..." style='width: 100%;' required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="number" class='newTrayInput newTrayInputNumber' name="ideal_temp" id='ideal_temp' placeholder="Ideal Temp (C)"  style='width: 100%;' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="number" class='newTrayInput newTrayInputNumber' name="ideal_daylight" id='ideal_daylight' placeholder="Ideal Min Of Daylight"  style='width: 100%;' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="number" class='newTrayInput newTrayInputNumber' name="num_trays" id='num_trays' placeholder="Number Of Trays" style='width: 100%;' required/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php include './bodyIncludes.php'; ?>
    <script>
        function mkDraggable() {
            $('#unreserved .plant').draggable({
                start: function (event, ui) {
                    $(this).tooltip('destroy');
                    //$(this).detach().appendTo('#right-main-area');
                },

                stop: function (event, ui) {
                    return true;

                },
                stack: ".chamber",
                scroll: false,
                zIndex: 999,

                revert: function (droppableObj) {
                    return droppableObj === false;
                }
            });
        }

        function mkDroppable(){
            $('.chamber:not(.closed)').droppable({
                drop: function (event, ui) {
                    var draggable = ui.draggable;
                    if ($(draggable).closest($(this)).length > 0) return false;

                    var plant_id = draggable.data('value');
                    var chamber_id = $(this).data('value');

                    var num_childern = $(this).children('.plant').length;

                    draggable.removeAttr('style');
                    draggable.css("left", (num_childern % 3) * draggable.width());
                    draggable.css('top', Math.floor(num_childern / 3) * draggable.height());
                    $('<button class="btn btn-danger btn-mini">Remove</button>').appendTo($(draggable).children('.container')[0]);
                    draggable.draggable('disable');
                    draggable.detach().appendTo($(this).children('.container')[0]);

                },
                accept: '.plant'
            });
        }

        $(document).ready(function () {

            mkDraggable();


            $('body').on('click','.plant',function(e){
               var element = this;
               var id = $(this).data('value');
                $.post('./ajax/htmlPlantDetails.php',{"plant_id":id},function(result){
                    $(element).attr('title', result).tooltip({html:true}).tooltip('fixTitle').tooltip('show');
                },'text');
            });


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
                        $.post('./ajax/htmlUpcomingSettings.php',{'to_date':(to_date.getTime()/1000) , 'from_date':(from_date.getTime()/1000) },
                            function(response){
                                $('#all_chambers').html(response);
                                mkDroppable();
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
                        $.post('./ajax/htmlUpcomingSettings.php',{'to_date':(to_date.getTime()/1000) , 'from_date':(from_date.getTime()/1000) },
                            function(response){
                                $('#all_chambers').html(response);
                                mkDroppable();
                            }
                            ,'html');
                    }
                }
            });



            $('#all_chambers').on('click', '.plant button', function (e) {
                var myPlant = $(this).parent().parent();
                $(this).remove();
                var myParent = myPlant.parent();
                var myClone = myPlant.clone();

                myClone.removeAttr('style');
                $('#unreserved_plants').append(myClone);
                myPlant.remove();


                var count = 0;
                myParent.children('.plant').each(function () {
                    $(this).css("left", (count % 3) * $(this).width());
                    $(this).css('top', Math.floor(count / 3) * $(this).height());
                    count++;
                });

                mkDraggable();
            });


            var $dialog = $('#newPlantDiv').dialog({
                autoOpen: false,
                height: 435,
                width: 491,
                modal: true,
                title: 'Create New Tray',
                buttons: {
                    "Create Plant": function () {
                        alert("Plant Created!");
                        $(this).dialog("close");
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    $('.newTrayInput').val('');
                }
            });

            $("#create_plant").click(function () {
                $dialog.dialog("open");
            });


            $(document).on('change', '.newTrayInput', function (e) {
                var data = {'ideal_temp': $('#ideal_temp').val(), 'ideal_daylight': $('#ideal_daylight').val(), 'num_trays': $('#num_trays').val()};
                $.post('./ajax/validateNewPlant.php', data,
                    function (result) {
                        if (result['ideal_temp'] === 'error') {
                            $('#ideal_temp').css('background-color', 'lightcoral');
                        } else if (result['ideal_temp'] === 'ok') {
                            $('#ideal_temp').css('background-color', 'white');
                        }
                        if (result['ideal_daylight'] === 'error') {
                            $('#ideal_daylight').css('background-color', 'lightcoral');
                        } else if (result['ideal_daylight'] === 'ok') {
                            $('#ideal_daylight').css('background-color', 'white');
                        }
                        if (result['num_trays'] === 'error') {
                            $('#num_trays').css('background-color', 'lightcoral');
                        } else if (result['num_trays'] === 'ok') {
                            $('#num_trays').css('background-color', 'white');
                        }
                    }, 'json');
            });

            $(document).on('keypress', '.newTrayInputNumber', function (e) {
                var a = [];
                var k = e.which;

                for (var i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k, a) >= 0))
                    e.preventDefault();
            });


        });

    </script>
</body>
</html>