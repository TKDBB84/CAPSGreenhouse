<?php

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__.'/../headIncludes.php'; ?>
    <link rel="stylesheet" href="/greenhouse/css/jquery.ganttView.css" type="text/css" />
    <style>
        .day-cell{
            width: 5px;
            margin: 0px;
            padding: 5px;
        }

        table td.highlighted {
            background-color:#EBC400;
        }

    </style>
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
            <div style="overflow-x: auto; width: 100%;">
            <table id="dtChambers" class="table table-bordered">
                <thead>
                    <tr data-row="-1">
                        <th colspan="1">Chamber</th>
                        <th colspan="31">Jan</th>
                        <th colspan="28">Feb</th>
                        <th colspan="31">Mar</th>
                        <th colspan="30">Apr</th>
                        <th colspan="31">May</th>
                        <th colspan="30">Jun</th>
                        <th colspan="31">Jul</th>
                        <th colspan="31">Aug</th>
                        <th colspan="30">Sep</th>
                        <th colspan="31">Oct</th>
                        <th colspan="30">Nov</th>
                        <th colspan="31">Dec</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-row="1">
                        <td colspan="1">Chamber 1</td>
                        <?php for($i=0 ; $i< 365 ; $i++){
                            echo '<td class="day-cell" data-value="',($i+1),'" style="padding: 0px;">&nbsp;</td>';
                        } ?>
                    </tr>
                    <tr data-row="2">
                        <td colspan="1">Chamber 2</td>
                        <?php for($i=0 ; $i< 365 ; $i++){
                            echo '<td class="day-cell" data-value="',($i+1),'" style="padding: 0px;">&nbsp;</td>';
                        } ?>
                    </tr>
                    <tr data-row="3">
                        <td colspan="1">Chamber 3</td>
                        <?php for($i=0 ; $i< 5 ; $i++){
                            echo '<td class="day-cell" data-value="',($i+1),'" style="padding: 0px;">&nbsp;</td>';
                        } ?>
                        <td colspan="135" style="background-color: red;">Arabidopsis Default (Jan 5th - May 20th)</td>
                        <?php for($i=0 ; $i< 225 ; $i++){
                            echo '<td class="day-cell" data-value="',($i+1),'" style="padding: 0px;">&nbsp;</td>';
                        } ?>
                    </tr>
                    <tr data-row="4">
                        <td colspan="1">Chamber 4</td>
                        <?php for($i=0 ; $i< 365 ; $i++){
                            echo '<td class="day-cell" data-value="',($i+1),'" style="padding: 0px;">&nbsp;</td>';
                        } ?>
                    </tr>
                    <tr style="visibility: hidden;">
                        <?php for($i=0 ; $i< 365 ; $i++){
                            echo '<td class="day-cell" style="padding: 5px;">&nbsp;</td>';
                        } ?>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-12">
            <button id="add_period" class="btn btn-primary pull-right disabled">Add Period</button>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-12">
            <div id="ganttChart"></div>
            <br/><br/>
            <div id="eventMessage"></div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../bodyIncludes.php'; ?>
<script type="text/javascript" src="/greenhouse/js/date.js"></script>
<script type="text/javascript" src="/greenhouse/js/jquery.ganttView.js"></script>
<script>

    function dateFromDay(year, day){
        var date = new Date(year, 0); // initialize a date in `year-01-01`
        var mv_date = new Date(date.setDate(day)); // add the number of days
        return mv_date.toDateString();
    }

    $(function () {

        var ganttData = [
            {
                id: 1, name: "Chamber 1", series: [
                { name: "Planned", start: new Date(2010,00,01), end: new Date(2010,00,03) }
            ]
            },
            {
                id: 2, name: "Feature 2", series: [
                { name: "Planned", start: new Date(2010,00,05), end: new Date(2010,00,20) },
                { name: "Actual", start: new Date(2010,00,06), end: new Date(2010,00,17), color: "#f0f0f0" },
                { name: "Projected", start: new Date(2010,00,06), end: new Date(2010,00,17), color: "#e0e0e0" }
            ]
            },
            {
                id: 3, name: "Feature 3", series: [
                { name: "Planned", start: new Date(2010,00,11), end: new Date(2010,01,03) },
                { name: "Actual", start: new Date(2010,00,15), end: new Date(2010,01,03), color: "#f0f0f0" }
            ]
            },
            {
                id: 4, name: "Feature 4", series: [
                { name: "Planned", start: new Date(2010,01,01), end: new Date(2010,01,03) },
                { name: "Actual", start: new Date(2010,01,01), end: new Date(2010,01,05), color: "#f0f0f0" }
            ]
            },
            {
                id: 5, name: "Feature 5", series: [
                { name: "Planned", start: new Date(2010,02,01), end: new Date(2010,03,20) },
                { name: "Actual", start: new Date(2010,02,01), end: new Date(2010,03,26), color: "#f0f0f0" }
            ]
            },
            {
                id: 6, name: "Feature 6", series: [
                { name: "Planned", start: new Date(2010,00,05), end: new Date(2010,00,20) },
                { name: "Actual", start: new Date(2010,00,06), end: new Date(2010,00,17), color: "#f0f0f0" },
                { name: "Projected", start: new Date(2010,00,06), end: new Date(2010,00,20), color: "#e0e0e0" }
            ]
            },
            {
                id: 7, name: "Feature 7", series: [
                { name: "Planned", start: new Date(2010,00,11), end: new Date(2010,01,03) }
            ]
            },
            {
                id: 8, name: "Feature 8", series: [
                { name: "Planned", start: new Date(2010,01,01), end: new Date(2010,01,03) },
                { name: "Actual", start: new Date(2010,01,01), end: new Date(2010,01,05), color: "#f0f0f0" }
            ]
            }
        ];

        $("#ganttChart").ganttView({
            data: ganttData,
            slideWidth: 900,
            behavior: {
                onClick: function (data) {
                    var msg = "You clicked on an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                    $("#eventMessage").text(msg);
                },
                onResize: function (data) {
                    var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                    $("#eventMessage").text(msg);
                },
                onDrag: function (data) {
                    var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                    $("#eventMessage").text(msg);
                }
            }
        });

        // $("#ganttChart").ganttView("setSlideWidth", 600);
    });

    $(document).ready(function(){

        $('.day-cell').each(function(){
           day = $(this).data('value');
           if(day !== null){
               $(this).prop('title',dateFromDay('2013',day));
           }

        });

        $(this).tooltip();

        $(function () {
            var isMouseDown = false;
            var tblrow = -1;
            $("#dtChambers td.day-cell")
                .mousedown(function () {
                    isMouseDown = true;
                    tblrow = $(this).parent('tr').data('row');
                    $(this).toggleClass("highlighted");
                    return false; // prevent text selection
                })
                .mouseover(function () {
                    if (isMouseDown) {
                        if($(this).parent('tr').data('row') == tblrow)
                            $(this).toggleClass("highlighted");
                    }
                })
                .bind("selectstart", function () {
                    return false; // prevent text selection in IE
                });

            $(document)
                .mouseup(function () {
                    isMouseDown = false;
                    tblrow = -1;
                    if($('.highlighted').length <= 1){
                        $('#add_period').addClass("disabled");
                    }else{
                        $('#add_period').removeClass("disabled");
                    }
                });

            });


        $('#add_period').click(function(){
           alert('lala');
        });

    });
</script>
</body>
</html>