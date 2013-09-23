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
            <h2>All Settings</h2>
            <table id="dtAllSettings">
                <thead>
                    <th>Setting Name</th>
                    <th>Temperature</th>
                    <th>Humidity</th>
                    <th>Light Length</th>
                    <th>Night Length</th>
                    <th></th>
                </thead>
                <tbody>
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
    <div class="row top-buffer">
        <div class="col-md-12">
            <button class="btn btn-primary pull-right">Add New Setting</button>
        </div>
    </div>
</div>
<?php include __DIR__.'/../bodyIncludes.php'; ?>
<script>
    $(document).ready(function(){
        var $dtAllSettings = $('#dtAllSettings').dataTable({
            "aoColumns": [
                { "mData": "name", "sWidth": "30%" },
                { "mData": "temperature" },
                { "mData": "humidity" },
                { "mData": "light_length" },
                { "mData": "night_length" },
                { "mData": "setting_id", "bSortable": false,
                    "mRender" : function(data, type, row ){
                        return '<button class="btn btn-danger delChamber" data-id="'+data+'">Delete</button>';
                    }
                }

            ],
            "sAjaxSource": '../ajax/dtJsonAllSettings.php',
            "bJQueryUI": true,
            "sServerMethod": "POST"
        });
    });
    /*
     'setting_id' => $this->chamber_id,
     'name' => $this->getName(),
     'temperature' => $this->getTemp(),
     'humidity' => $this->getHumidity(),
     'light_length' => $this->getDayLength(),
     'night_length' => $this->getNightLength(),
     'first_lights_on' => $this->getFirstLightsOn()
     */
</script>
</body>
</html>