<?php
require_once __DIR__.'/../classes/DBconnection.php';
$pdo_dbh = DBconnection::getFactory()->getConnection();
if(!isset($_POST['lab_id']) ){
    die('<h2>No Lab Id Supplied</h2>');
}



$lab_id = $_POST['lab_id'];
if($lab_id == 3){
    $lab_name = 'Jeffrey\'s Lab Of Funness';
}



?>
<div class="row" style="">
    <div class="col-md-12">
        <button class='btn btn-danger pull-right'>Delete This Lab</button>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <h3>Current Members:</h3>
        <ul id="all_members">
            <li><span class='lab_owner'>Jeffrey Campbell - Owner</span></li>
            <li id='lab_member_1'>
                <span class='lab_manager'>Tom -
                    <select class='adj-rank form-control' data-value='{&quot;user_name&quot;:&quot;Tom&quot;,&quot;lab_id&quot;:<?php echo $lab_id ?>,&quot;user_id&quot;:1,&quot;rank&quot;:2}'>
                        <option value='2' selected>Manager</option>
                        <option value='1'>Member</option>
                        <option value='-1'>Remove...</option>
                    </select>
                </span>
            </li>
            <li id='lab_member_2'>
                <span class='lab_member'>Dick -
                    <select class='adj-rank form-control' data-value='{&quot;user_name&quot;:&quot;Dick&quot;,&quot;lab_id&quot;:<?php echo $lab_id ?>,&quot;user_id&quot;:2,&quot;rank&quot;:1}'>
                        <option value='2'>Manager</option>
                        <option value='1' selected>Member</option>
                        <option value='-1'>Remove...</option>
                    </select>
                </span>
            </li>
            <li id='lab_member_3'>
                <span class='lab_member'>Harry -
                    <select class='adj-rank form-control' data-value='{&quot;user_name&quot;:&quot;Harry&quot;,&quot;lab_id&quot;:<?php echo $lab_id ?>,&quot;user_id&quot;:3,&quot;rank&quot;:1}'>
                        <option value='2'>Manager</option>
                        <option value='1' selected>Member</option>
                        <option value='-1'>Remove...</option>
                    </select>
                </span>
            </li>
        </ul>
        <button class="btn btn-primary btn-block">Add Member</button>
    </div>
    <div style='border-left: 1px solid black;' class="col-md-9">
        <h3>Accounts: </h3>

        <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">Account 1</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Chart-Field Info:</div>
                        <div class="col-md-3">
                            <input type='text' class="form-control"/>
                        </div>
                        <div class="col-md-3">
                            <input type='text' class="form-control"/>
                        </div>
                        <div class="col-md-3">
                            <input type='text' class="form-control"/>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">Account 2</div>
                </div>
                <div class="row">
                    <div class="col-md-3">Chart-Field Info:</div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">Account 1</div>
                </div>
                <div class="row">
                    <div class="col-md-3">Chart-Field Info:</div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <input type='text' class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 top-buffer">
                <button class="btn btn-primary pull-right">Save</button>
            </div>
        </ul>
    </div>
</div>