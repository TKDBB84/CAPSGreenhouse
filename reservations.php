<?php
    include_once 'header.php';
    include_once './classes/DBconnection.php';
    /* @var $pdo_dbh PDO */
    $pdo_dbh = DBconnection::getFactory()->getConnection();

    $all_periods = array();
    $result = $pdo_dbh->query("SELECT chamber_name as `name`,temperature as `temp`,`daylight (min)` as `light`,`total space` as `space` FROM periods AS p JOIN chambers AS c ON c.chamber_id = p.fk_chamber_id JOIN settings AS s ON s.setting_id = p.fk_setting_id WHERE p.start_date < NOW() AND p.end_date > NOW()");
    if (is_array($result)) {
        foreach ($result as $peroid) {
            $all_periods[] = $peroid;
        }
    }
    unset($result, $peroid);
?>
<!DOCTYPE html>
<html lang="en">
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
            <div id="right-main-area" style="" class="col-md-12">
                <div id="currentReservationsTable">
                    <table id="dtCurrentReservations">
                        <thead>
                        <th>Plant</th>
                        <th>Chamber</th>
                        <th>Settings</th>
                        <th>Final Plant Date</th>
                        <th>Final Harvest Date</th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>
                        <td></td>
                        <td></td>
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
    <?php include './bodyIncludes.php'; ?>
    <script>
        $(document).ready(function () {




            var $dtUpcomingPeriods = $('#dtCurrentReservations').dataTable( {
                "aoColumns": [
                    { "mData": "Plant" },
                    { "mData": "Chamber" },
                    { "mData": "Settings",
                      "mRender" : function(data){
                                    return "Temp: "+data.Temp+"&deg;C<br />"+
                                           "Daylight: "+data.Daylight+" (min)<br />"+
                                           "Humidity: "+data.Humidity+"%";
                                  }
                    },
                    { "mData": "Final Plant Date",
                        "mRender" : function(data){
                            var final_plant_date =  new Date(data*1000);
                            return formateDate(final_plant_date);
                        }
                    },
                    { "mData": "Final Harvest Date",
                        "mRender" : function(data){
                            var final_harvest_date =  new Date(data*1000);
                            return formateDate(final_harvest_date);
                        }
                    },
                    { "mData": "plant_id", "bSortable": false,
                        "mRender" : function(data, type, row ){
                            return '<button class="btn btn-info mk-label" data-id=\' {&quot;plant_id&quot;: '+row.plant_id+', &quot;period_id&quot;:'+row.period_id+'} \'>Print Label</button>';
                        }
                    },
                    { "mData": "chamber_id" , "bSortable": false,
                        "mRender" : function(data, type, row ){

                            return '<button class="btn btn-danger del-res" data-id=\' {&quot;plant_id&quot;: '+row.plant_id+', &quot;period_id&quot;:'+row.period_id+'} \'>Cancel Reservation</button>';
                        }
                    },
                    { "mData": "period_id" , "bSortable": false, "bVisible": false }
                ],
                "sAjaxSource": './ajax/dtJsonCurrentReservations.php',
                "bJQueryUI": true,
                "sServerMethod": "POST"
            } );







/*
            $.post('./ajax/jsonCurrentReservations.php', function (result) {
                var table = $('<table class="table table-striped">' +
                    '               <tr>' +
                    '                    <th>Plant</th>' +
                    '                    <th>Chamber</th>' +
                    '                    <th>Settings</th>' +
                    '                    <th>Final Plant Date</th>' +
                    '                    <th>Final Harvest Date</th>' +
                    '                    <th></th>' +
                    '                    <th></th>' +
                    '               </tr>' +
                    '               <tr id="empty_results">' +
                    '                    <td colspan="7" align="center">No Reservations Found</td>' +
                    '               </tr>' +
                    '           </table>');

                if(!$.isEmptyObject(result)){
                    $(table).find('#empty_results').remove();
                    $.each(result.reservations,function(i,item){
                        var final_harvest_date =  new Date(item.final_harvest_date*1000);
                        var final_plant_date =  new Date(item.final_plant_date*1000);
                        $('<tr>' +
                            '<td class="res_plant" data-id="'+item.plant_id+'">'+item.plant_name+'</td>' +
                            '<td class="res_chamber" data-id="'+item.chamber_id+'">'+item.chamber_name+'</td>' +
                            '<td class="res_period" data-id="'+item.period_id+'">'+
                                'Temp: '+item.temp+'&deg;C'+'<br />' +
                                'Daylight: '+item.min_daylight+' min'+'<br />' +
                                'Humidity: '+item.humidity+'% '+
                            ' </td>' +
                            '<td>'+formateDate(final_plant_date)+'</td>' +
                            '<td>'+formateDate(final_harvest_date)+'</td>' +
                            '<td><button class="btn btn-info mk-label" data-id=\' {&quot;plant_id&quot;: '+item.plant_id+', &quot;period_id&quot;:'+item.period_id+'} \'>Print Label</button>' +
                            '<td><button class="btn btn-danger del-res" data-id=\' {&quot;plant_id&quot;: '+item.plant_id+', &quot;period_id&quot;:'+item.period_id+'} \'>Cancel Reservation</button>' +
                          '</tr>').appendTo($(table));
                    });
                }
                $(table).appendTo('#right-main-area');
            },'json');*/

            $('#right-main-area').on('click','.mk-label',function(){
                var pushed = $.parseJSON($(this).data('id'));

            });

            $('#right-main-area').on('click','.del-res',function(){
               var pushed = $.parseJSON($(this).data('id'));
            });

        });

        function formateDate(d){
            var m_names = new Array("January", "February", "March",
                "April", "May", "June", "July", "August", "September",
                "October", "November", "December");

            var curr_date = d.getDate();
            var sup = "";
            if (curr_date == 1 || curr_date == 21 || curr_date ==31)
            {
                sup = "st";
            }
            else if (curr_date == 2 || curr_date == 22)
            {
                sup = "nd";
            }
            else if (curr_date == 3 || curr_date == 23)
            {
                sup = "rd";
            }
            else
            {
                sup = "th";
            }

            var curr_month = d.getMonth();
            var curr_year = d.getFullYear();

            return curr_date + "<SUP>" + sup + "</SUP> "+ m_names[curr_month] + " " + curr_year;
        }

    </script>
</body>

</html>