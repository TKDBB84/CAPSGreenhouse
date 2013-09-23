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
                <h2>Order Of Entry</h2>
                <ol>
                    <li>Chamber-4</li>
                    <li>Chamber-1</li>
                    <li>Chamber-2</li>
                    <li>Chamber-8</li>
                    <li>Chamber-2</li>
                    <li>Chamber-3</li>
                    <li>Chamber-6</li>
                </ol>
                <h2>Current Chamber Settings</h2>
                <table id="dtCurrentSettings">
                    <thead>
                        <tr>
                            <th>Growth Chamber:</th>
                            <th>Temp (C)</th>
                            <th>Daylight (min)</th>
                            <th>Total Space</th>
                            <th>Available Space</th>
                            <th>Pest Type</th>
                            <th>Pest Count</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center">Chamber-1</td>
                            <td align="center">30</td>
                            <td align="center">360</td>
                            <td align="center">50</td>
                            <td align="center">12</td>
                            <td align="center">None</td>
                            <td align="center">0</td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td align="center">Chamber-2</td>
                            <td align="center">55</td>
                            <td align="center">420</td>
                            <td align="center">15</td>
                            <td align="center">*CLOSED*</td>
                            <td align="center">Flies</td>
                            <td align="center">50</td>
                            <td align="center"><a href="#" onClick="notice(2);">NOTICE!!</a></td>
                        </tr>
                        <tr>
                            <td align="center">Chamber-3</td>
                            <td align="center">75</td>
                            <td align="center">300</td>
                            <td align="center">5</td>
                            <td align="center">1</td>
                            <td align="center">Spiders</td>
                            <td align="center">100</td>
                            <td align="center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include './bodyIncludes.php' ?>
    <script>
        $(document).ready(function () {

            $('#dtCurrentSettings').dataTable({
                "bJQueryUI": true,
                "iDisplayLength" : -1,
                "bFilter": false,
                'bAutoWidth':false
            });

            $('#dtCurrentSettings_length').html("&nbsp;");

        });

        function notice(id) {
            alert("Notice For Chamber " + id);
        }
    </script>
</body>

</html>