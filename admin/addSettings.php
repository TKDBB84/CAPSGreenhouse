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
            <button id="addSetting" class="btn btn-primary pull-right">Add New Setting</button>
        </div>
    </div>
</div>
<div id="mkSetting" style="">
    <fieldset id="fs_dates">
        <div class="row">
            <div class="col-md-12">
                <label class="control-label">Tempature:
                    <div class="input-group">
                        <input type="text" name="period_type" class="form-control" id="setting" value="0" checked="true" />
                        <span class="input-group-addon">&deg;C</span>
                    </div>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Humidity:
                    <div class="input-group">
                    <input type="text" name="period_type" class="setting form-control" id="setting" value="0" checked="true"/>
                    <span class="input-group-addon">%</span>
                    </div>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Light Length:
                    <div class="input-group">
                    <input type="text" name="period_type" class="setting form-control" id="setting" value="0" checked="true"/>
                    <span class="input-group-addon">min</span>
                    </div>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Night Length:
                    <div class="input-group">
                        <input type="text" name="period_type" class="setting form-control" id="setting" value="0" checked="true"/>
                        <span class="input-group-addon">min</span>
                    </div>
                </label>
            </div>
        </div>
    </fieldset>
</div>
<?php include __DIR__.'/../bodyIncludes.php'; ?>
<script>
    $(document).ready(function(){

        $dialog = $('#mkSetting').dialog({
            autoOpen: false,
            height: 381,
            width: 290,
            modal: true,
            title: 'Create Settings',
            buttons: {
                "Save" : function(){
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

        $('#addSetting').click(function(){
            $dialog.dialog("open");
        });


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