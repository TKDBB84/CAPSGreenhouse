<?php
    require_once __DIR__.'/../classes/Setting.php';
    require_once __DIR__.'/../classes/Chamber.php';

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    $allsettings = Setting::getAllSettings();

    $year = date('Y');
    $days_in_year = 365 + ((checkdate( 2 , 29 , $year))?1:0);

    $allChambers = Chamber::getAllChambers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__.'/../headIncludes.php'; ?>
    <style>
        .day-cell{
            width: 5px;
            margin: 0px;
            padding: 5px;

            height: 50px;
        }

        .innerDiv{

            position: relative;
            white-space: nowrap;

            text-overflow: ellipsis;
            overflow: hidden
        }

        table td.highlighted {
            background-color:#EBC400;
        }


        #overlay {
            display:none;
            position:absolute;
            background:#fff;
        }
        #img-load {
            position:absolute;
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
            <span class="pull-right">Year: <input id="year" type="number" value="<?php echo date('Y'); ?>" /></span>
            <div id="overlay">
                <img src="/greenhouse/img/ajax-loader.gif"
                     id="img-load" />
            </div>
            <div id="gantt" style="overflow-x: auto; width: 100%;">
             <!--ajax table:   Something to consider, Perhaps I could ajax in more columns for the table
             as the scroll bar reaches an end point.  It is important to remember if I do this, to include
              a global variable "currently processing" to ensure I don't fire the same ajax request multiple times,
              Perhaps the gobal variable could be the year that was just loaded, and iterate it: using the year box
              at the top to maintain state, on change it resets everything to defaults (?)-->
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

<div id="settings" style='display: none;'>
    <div class="row">
        <div class="col-md-12">

            <div class="radio">
                <label>
                    <input type="radio" name="period_type" class="setting_selector" id="setting" value="0" checked="true"/>
                    Growth Period:
                </label>
            </div>
            <fieldset id="fs_setting">
                <select id="sel_setting" class="form-control">
                    <?php
                        /** @var $setting Setting */
                        foreach($allsettings as $setting){
                            echo '<option value="',$setting->setting_id,'">',$setting->getName(),'</option>';
                        }
                    ?>
                </select>
            </fieldset>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-12">
            <div class="radio">
                <label>
                    <input type="radio" name="period_type" class="setting_selector" id="maintenance" value="1" />
                    Maintenance
                </label>
            </div>
            <fieldset id="fs_maintenance" disabled="true">
                <input type="text" class="form-control" name="maintenance_reason" placeholder="Description..."/>
            </fieldset>
        </div>
    </div>
</div>

<div id="period_details" style='display: none;'>
    <fieldset id="fs_dates">
        <div class="row">
            <div class="col-md-12">
                <label>
                Final Plant Date: <input type="text" class="form-control jqueryui-date" name="final_plant_date" id="final_plant_date"/>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>
                    Final Harvest Date: <input type="text" class="form-control jqueryui-date" name="final_harvest_date" id="final_harvest_date"/>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>
                    Spaces To Hold Back:
                    <input type="number" class="form-control" name="held_back" />
                </label>
            </div>
        </div>
    </fieldset>
</div>
<?php include __DIR__.'/../bodyIncludes.php'; ?>
<script type="text/javascript" src="/greenhouse/js/date.js"></script>
<script>
    function dateFromDay(year, day) {
        var date = new Date(year, 0); // initialize a date in `year-01-01`
        var mv_date = new Date(date.setDate(day)); // add the number of days
        return mv_date.toDateString();
    }

    function TableCell(period_id, start_date, end_date, start_day, description) {
        this.period_id = period_id;
        this.start_date =  new Date(0);
        this.start_date.setUTCSeconds(start_date);
        this.end_date =  new Date(0);
        this.end_date.setUTCSeconds(end_date);
        this.start_cell = start_day;
        this.description = description;
    }

    function SortByStart(a, b) {
        return ((a.start_cell < b.start_cell) ? -1 : ((a.start_cell > b.start_cell) ? 1 : 0));
    }


    function daysInMonth(iMonth, iYear)
    {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    }

    $(document).ready(function () {

        $( ".jqueryui-date" ).datepicker();
        var currYear = new Array();
        var firstRun = true;

        var $dialog2 = $('#period_details').dialog({
            autoOpen: false,
            height: 305,
            width: 491,
            modal: true,
            title: 'Date Details',
            buttons: {
                "Save" : function(){
                    alert("SAVED!");
                    var unixts_final_plant_date = $('#final_plant_date').datepicker('getDate') / 1000;
                    var unixts_final_harvest_date = $('#final_harvest_date').datepicker('getDate') / 1000;
                    //var chambers = array();
                    $('.chamber_row').each(function(){
                       var days = $(this).find('.highlighted');

                        // each element in the "days" var, contains the
                        // chamber for which it belongs, along with the
                        // day number of year (1-366) both within the
                        // data-* array.  This should yield me enough
                        // information to transfer them to a php script
                        // via ajax and make the reservation, I just need
                        // to somehow pass the chosen settings along with
                        // it.  Maybe update a hidden input somewhere on
                        // the page?  I don't think there is a way to pass
                        // a custom argument to the jQueryUI dialog box.

                    });
                    $(this).dialog("close");
                },
                cancel: function() {
                    $(this).dialog("close");
                }
            },
            close: function(){
                $('#final_plant_date').val('');
                $('#final_harvest_date').val('');
                $('#held_back').val(0);
            }
        });

        var $dialog = $('#settings').dialog({
            autoOpen: false,
            height: 305,
            width: 491,
            modal: true,
            title: 'Select Setting',
            buttons: {
                "Next": function () {
                    //alert("Setting Added!");

                    $(this).dialog("close");
                    $dialog2.dialog("open");
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {
                $("#setting").prop("checked", true);
                $("#fs_setting").prop("disabled", false);
                $("#fs_maintenance").prop("disabled", true);
            }
        });

        $('.setting_selector').change(function () {
            if ($(this).val() == 0) {
                $("#fs_setting").prop("disabled", false);
                $("#fs_maintenance").prop("disabled", true);

            } else {
                $("#fs_setting").prop("disabled", true);
                $("#fs_maintenance").prop("disabled", false);
            }
        });


        var isMouseDown = false;
        var tblrow = -1;
        $("#gantt").on('mousedown', 'td.day-cell', function () {
            isMouseDown = true;
            tblrow = $(this).parent('tr').data('row');
            $(this).toggleClass("highlighted");
            return false; // prevent text selection
        }).on('mouseover', 'td.day-cell', function () {
                if (isMouseDown) {
                    if ($(this).parent('tr').data('row') == tblrow)
                        $(this).toggleClass("highlighted");
                }
            }).bind("selectstart", function () {
                return false; // prevent text selection in IE
            });

        $(document).mouseup(function () {
            isMouseDown = false;
            tblrow = -1;
            if ($('.highlighted').length < 1) {
                $('#add_period').addClass("disabled");
            } else {
                $('#add_period').removeClass("disabled");
            }
        });



        $('#add_period').click(function () {
            $dialog.dialog("open");
        });


        $('#year').change(function () {
            currYear = [$("#year").val()];
            $.post('/greenhouse/ajax/jsonGanttCells.php', {
                'years' : currYear
            }, function (res) {
                var Years = res.Years;
                var $ganttTable = getBlankTable(Years);
                var numDays = Years[0].Days;
                var allChambers = res.Chambers;
                $.each(allChambers, function (key, chamber) {
                    var id = "#chamber_"+chamber.chamber_id;
                    $chamberRow = $ganttTable.find(id);
                    $chamberRow.append(mkGanttRow(chamber,numDays));
                });

                var $headerRow = $ganttTable.find('#months');
                $headerRow.append(mkHeaderRow(Years[0].Year));

                var $finalRow = $ganttTable.find('#footerRow');
                $finalRow.append(mkGanttFooter(numDays));

                $('#gantt').html($ganttTable);

                updateToolTips(Math.min.apply(null, currYear));

                updateOverlay();

            }, 'json');
        });

        function updateToolTips(minYear){
            $('.day-cell').each(function () {
                day = $(this).data('day');
                if (day !== null) {
                    $(this).prop('title', dateFromDay(minYear, day));
                }

            });
            $(document).tooltip();
        }


        function getBlankTable(year){
            var $ganttTable = $('<table id="dtChambers" class="table table-bordered top-buffer"></table>');
            var $headerRow = $('<tr id="months" data-row="-1"><th colspan="1">Chamber</th></tr>');
            $ganttTable.append($headerRow);
            <?php
             foreach($allChambers as $chamber){
             /** @var $chamber Chamber */
                echo 'var $chamberRow'.$chamber->chamber_id.' = $("<tr class=\\"chamberRow\\" id=\\"chamber_'.$chamber->chamber_id.'\\"><th>'.$chamber->getName().'</th></tr>");';
                echo '$ganttTable.append($chamberRow'.$chamber->chamber_id.');';
             }
            ?>
            $finalRow = $('<tr style="visibility: hidden; id="footerRow"><th colspan="1">&nbsp;</th></tr>');
            $ganttTable.append($finalRow);
            return $ganttTable;
        }

        function mkGanttFooter(numDays){
            for(var k=0 ; k < numDays ; k++){
                $finalRow.append('<td class="day-cell" style="padding: 5px;">&nbsp;</td>');
            }
            return $finalRow;
        }


        var processing = false;
        $('#gantt').scroll(function(){
            if(processing) return true;

            var percent =  100 * this.scrollLeft / (this.scrollWidth-this.clientWidth);
            if (percent > 99){
                processing = true; //sets a processing AJAX request flag
                currYear[0] = +currYear[0] + 1;
                $.post('/greenhouse/ajax/jsonGanttCells.php', {
                    'years' : currYear
                }, function (res) {
                    var Years = res.Years;
                    var $ganttTable = $('#dtChambers')//getBlankTable(Years);
                    var numDays = Years[0].Days;
                    var allChambers = res.Chambers;
                    $.each(allChambers, function (key, chamber) {
                        setTimeout(function () {
                            var id = "#chamber_"+chamber.chamber_id;
                            $chamberRow = $ganttTable.find(id);
                            $chamberRow.append(mkGanttRow(chamber,numDays));
                        },0);
                    });

                    setTimeout(function () {
                        var $headerRow = $ganttTable.find('#months');
                        $headerRow.append(mkHeaderRow(Years[0].Year));
                    }, 0);

                    setTimeout(function () {
                    var $finalRow = $ganttTable.find("#footerRow");
                    $finalRow.append(mkGanttFooter(numDays));
                    }, 0);


                    updateToolTips(Math.min.apply(null, currYear));

                    updateOverlay();
                    processing = false;
                }, 'json');
            }

        });

        function mkHeaderRow(year){
            $headerRow = $('<th colspan="31">Jan '+year+'</th>' +
                '<th colspan="'+daysInMonth(1,year)+'">Feb '+year+'</th>' +
                '<th colspan="31">Mar '+year+'</th>' +
                '<th colspan="30">Apr '+year+'</th>' +
                '<th colspan="31">May '+year+'</th>' +
                '<th colspan="30">Jun '+year+'</th>' +
                '<th colspan="31">Jul '+year+'</th>' +
                '<th colspan="31">Aug '+year+'</th>' +
                '<th colspan="30">Sep '+year+'</th>' +
                '<th colspan="31">Oct '+year+'</th>' +
                '<th colspan="30">Nov '+year+'</th>' +
                '<th colspan="31">Dec '+year+'</th>');
            return $headerRow;
        }


        function mkGanttRow(chamberObj,totalDays){
            var curr_id = chamberObj.chamber_id;
            var chamber_name = chamberObj.chamber_name;
            //var $row = $('#chamber_'+curr_id);
            var allCells = new Array();
            var i = 0;
            $.each(chamberObj.chamber_cells, function (key, cell) {
                allCells[i++] = new TableCell(cell.period_id,cell.start_time,cell.end_time,cell.start_day,cell.description);
            });
            //$row.append('<th colspan="1">'+chamber_name+'</th>');
            allCells.sort(SortByStart);
            i = 0;
            var oneDay = 24*60*60*1000;
            var bgcolors = ['red','gold'];
            var bgSelect = true;//, isFirst = true;
            var $row = $('');
            for (var j = 0; j < totalDays; j++)
                if (i<allCells.length && j == (allCells[i].start_cell)) {
                    var colspan = (Math.round(Math.abs((allCells[i].start_date.getTime() - allCells[i].end_date.getTime())/(oneDay)))+1);
                    $row = $row.add('<td style="background-color: '+bgcolors[bgSelect?0:1]+'" colspan="' + colspan + '"><div class="innerDiv">'+
                                  allCells[i].description + ' ( ' +
                                  allCells[i].start_date.toDateString()+' - '+allCells[i].end_date.toDateString()+ ' ) ' +
                                '</div></td>');
                    bgSelect = !bgSelect;
                    j+=(colspan - 1);
                    i++;
                } else {
                    $row = $row.add('<td colspan="1" class="day-cell" data-chamber="'+curr_id+'" data-day="'+(j+1)+'" style="padding: 0px;">&nbsp;</td>');
                }
            return $row;
        }


        function updateOverlay(){/*
            $("#overlay").css({
                opacity : .5,
                top     : $('#gantt').top,
                width   : $('#gantt').outerWidth(),
                height  : $('#gantt').outerHeight()
            });

            $("#img-load").css({
                top  : ($('#dtChambers').height() / 2),
                left : ($(window).width() / 2)
            });*/
        }


        $('#year').trigger('change');


        $('#overlay').ajaxStart(function() {
            $("#overlay").css({
                opacity : .5,
                top     : $('#gantt').top,
                width   : $('#gantt').outerWidth(),
                height  : $('#gantt').outerHeight()
            });

            $("#img-load").css({
                top  : ($('#dtChambers').height() / 2),
                left : ($(window).width() / 2)
            });
            $(this).show();
        }).ajaxStop(function() {
                $(this).hide();
            });
    });


</script>
</body>
</html>