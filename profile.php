<?php
include_once 'header.php';
include_once './classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();


$stmt_get_user_email = $pdo_dbh->prepare('SELECT `email`,`first_name`,`last_name`,`phone`,`address` FROM `Users` WHERE `user_id`= :user_id');
$stmt_get_user_email->bindValue(':user_id', $_SESSION['gh_user_id'], PDO::PARAM_INT);
$stmt_get_user_email->execute();
$result = $stmt_get_user_email->fetchColumn();

if ($result !== false) {

} else { // die('Your User ID Appears To Be Invalid... Wat?!?');
    $user['email'] = "campbell.1333@osu.edu";
    $user['first_name'] = 'Jeffrey';
    $user['last_name'] = 'Campbell';
    $user['phone'] = '6146881208';
    $user['address'] = '189 Rightmire Hall';
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './headIncludes.php'; ?>
    <style>
        .panel{
            background-color: #eee;
        }
    </style>
</head>
<body>

    <div class="page-header">
        <img src="img/rightmire.png" alt="rightmire" class=".img-responsive" />
        <h1 style="display: inline-block">GreenHouse Support Facility</h1>
    </div>
    <?php include_once 'nav.php'; ?>
    <div class="container">
        <h2>Manage Profile</h2>
        <div class='row'>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>My Profile</h4></div>
                    <div class="panel-body">
                        <div class='form-group'>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <label for='email'>Email Address: </label>
                                    <input class="form-control" type='text' id='email' value='<?php $user['email']; ?>' placeholder="EMail"/>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <label for='fname'>First Name: </label>
                                    <input class="form-control" type='text' id='fname' value='<?php $user['first_name']; ?>' placeholder="First Name"/>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <label for='lname'>Last Name: </label>
                                    <input class="form-control" type='text' id='lname' value='<?php $user['last_name']; ?>' placeholder="Last Name"/>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <label for='phone'>Phone Number: </label>
                                    <input class="form-control" type='text' id='phone' value='<?php $user['phone']; ?>' placeholder="XXX-XXX-XXXX"/>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <label for='address'>Office Address: </label>
                                    <textarea class="form-control" id='address' rows='3' placeholder="Office Address..."><?php echo $user['address']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style='' class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Labs:</h4>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">CAPS Administration</button>
                            <button class="btn btn-default">Grotewold Lab</button>
                            <button class="btn btn-default manage">Jeffrey's Lab Of Funness</button>
                        </div>
                    </div>
                    <div id="lab_details" class="panel-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include './bodyIncludes.php'; ?>
<script>
    $(document).ready(function () {
        $('.manage').click(function () {
            $button = $(this)
            $('.manage').prop('disabled', false);
            $.post('./ajax/htmlLabDetails.php', {'lab_id': $(this).val()}, function (result) {
                $('#lab_details').html(result);
                $button.prop("disabled", true);
            }, 'html');
        });

        $('#lab_details').on('change','.adj-rank',function(){
           var data = $(this).data('value');
           var user_id = data.user_id;
           var user_name = data.user_name;
           var lab_id = data.lab_id;
           var rank_id = $(this).val();
           var curr_rank = data.rank;
           var the_select = this;
           if(rank_id === '-1'){
               $('<div></div>').appendTo('body')
                   .html('<div><h6>Are You Sure You Want To Remove '+user_name+'</h6></div>')
                   .dialog({
                       modal: true, title: 'Confirm Removal', zIndex: 10000, autoOpen: true,
                       width: 'auto', resizable: false,
                       buttons: {
                           Yes: function () {
                               /*
                                * This funciton needs some ajax database removal
                                * magic!
                                */
                               $('#all_members').find('#lab_member_'+user_id)[0].remove();
                               $(this).dialog("close");
                           },
                           No: function () {
                               $(the_select).val(curr_rank);
                               $(this).dialog("close");
                           }
                       },
                       close: function (event, ui) {
                           $(this).remove();
                       }
                   });
           }
        });

        $('.remove').click(function () {
            var id = $(this).val();
            var span_id = '#lab_' + id.toString();
            $('<div></div>').appendTo('body')
                .html('<div><h6>Are You Sure You Want To Remove Yourself From This Lab?</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm Removal', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            /*
                             * This funciton needs some ajax database removal
                             * magic!
                             */
                            $(span_id).remove();
                            $(this).dialog("close");
                        },
                        No: function () {

                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });


        });

    });
</script>
</body>
</html>