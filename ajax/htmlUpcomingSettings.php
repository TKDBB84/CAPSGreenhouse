<?php
require_once __DIR__.'/../classes/DBconnection.php';
$pdo_dbh = DBconnection::getFactory()->getConnection();
if(!isset($_POST['from_date']) || !isset($_POST['to_date'])){
    die('<h2>No Dates Supplied</h2>');
}

$from_date = date('M jS Y',$_POST['from_date']);
$to_date = date('M jS Y',$_POST['to_date']);

$from_harvest = date('M jS Y',  strtotime('+3 Months', $_POST['from_date']));
$to_harvest = date('M jS Y',  strtotime('+3 Months', $_POST['to_date']));

?>
<div class="row">
    <div class='chamber col-md-3' data-value='1'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <div class="bottom-aligned">
                Chamber 1<br/>
                <span style="font-weight: bolder;">4 Trays Of Space Available</span><br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
    <div class='chamber closed col-md-3' data-value='5'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <span class='closed_text'>Full!</span>
            <div class="bottom-aligned">
                Chamber 5<br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class='chamber closed col-md-3' data-value='4'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <span class='closed_text'>Full!</span>
            <div class="bottom-aligned">
                Chamber 4<br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
    <div class='chamber col-md-3' data-value='3'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <div class="bottom-aligned">
                Chamber 3<br/>
                <span style="font-weight: bolder;">1 Tray Of Space Available</span><br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class='chamber col-md-3' data-value='7'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <div class="bottom-aligned">
                Chamber 7<br/>
                <span style="font-weight: bolder;">2 Tray Of Space Available</span><br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
    <div class='chamber closed col-md-3' data-value='8'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <span class='closed_text'>Full!</span>
            <div class="bottom-aligned">
                Chamber 8<br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class='chamber closed col-md-3' data-value='9'>
        <div class="container" style="position: relative; height: 100%; width: 100%;">
            <span class='closed_text'>Full!</span>
            <div class="bottom-aligned">
                Chamber 9<br/>
                Plant By Date: May 1st 2013<br/>
                Harvest Date: Aug 30th 2013<br/>
                Temperature: 22&deg;<br/>
                Humidity: 15%
            </div>
        </div>
    </div>
</div>