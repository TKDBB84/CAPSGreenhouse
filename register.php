<?php
include_once 'header.php';
require_once './classes/DBconnection.php';
/* @var $pdo_dbh PDO */
$pdo_dbh = DBconnection::getFactory()->getConnection();

$all_labs = array();
foreach ($pdo_dbh->query("SELECT `lab_id`,`lab_name` FROM `Labs` ORDER BY `lab_name` ASC") as $row) {
    $all_labs[$row['lab_id']] = $row['lab_name'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>BioTech Support System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel='stylesheet'
          href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/<?php echo $google_theme; ?>/jquery-ui.min.css'
          type='text/css'/>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./css/gh_start.css" type="text/css"/>
</head>
<body>
<div class="register_title">
    <span>Register As A New User</span>
</div>
<div id="register_container">
    <form method="POST" action="./mkNewUser.php" id="new_user_form">
        <div>
            <label for="email">Email Address</label>
            <input type="text" name="email" id="email" required/>

            <div class="valid" id='email_valid_cell'></div>
        </div>
        <div>
            <label for="email">First Name</label>
            <input type="text" name="firstname" class='name' id="fname" required/>

            <div class="valid" id='fname_valid_cell'></div>
        </div>
        <div>
            <label for="email">Last Name</label>
            <input type="text" name="lastname" class='name' id='lname' required/>

            <div class="valid" id='lname_valid_cell'></div>
        </div>
        <div>
            <label for="email">Password</label>
            <input type="password" name="password_1" id='password' required/>

            <div class="valid" id='password_valid_cell'></div>
        </div>
        <div>
            <label for="email">Confirm</label>
            <input type="password" name="password_2" id='confirm' required/>

            <div class="valid" id='confirm_valid_cell'></div>
        </div>
        <div>
            <label for="email">Select Lab</label>
            <select name="lab" id="lab_select" style='width: 153px;'>
                <option value="-2" selected>Select A Lab</option>
                <?php
                foreach ($all_labs as $id => $lab_name) {
                    echo '<option value="', $id, '">', $lab_name, '</option>';
                }
                ?>
                <option value="-1">New Lab...</option>
            </select>

            <div id="new_lab_div" class="new_lab">
                <input type="text" name="new_lab" id="new_lab_text" class="new_lab" style="margin-left: 20px;"/>
            </div>
            <div class="valid" id='lab_valid_cell'></div>
        </div>
        <div id="lower">
            <input type="submit" value="Register">
        </div>
        <!--/ lower-->
    </form>
    <p><a href="./index.php">Return To Login</a></p>
</div>
<?php include './bodyIncludes.php'; ?>
<script>
    $(document).ready(function () {
        $('.new_lab').hide();

        var error_img = '<img src="./img/red_x.png" style="height: 30px; width: 30px;"/> ';
        var ok_img = '<img src="./img/green_check.png" style="height: 30px; width: 30px;"/>';

        $('#lab_select').change(function () {
            if ($(this).val() === '-1') {
                $('.new_lab').show();
                $('#new_lab_text').focus();
            } else {
                $('.new_lab').hide();
            }
        });

        $('#email').change(function () {
            $.post('./ajax/validateUsername.php', {'email': $(this).val()}, function (result) {
                if (result === '1') {
                    $('#email_valid_cell').html(ok_img);
                } else {
                    $('#email_valid_cell').html(error_img + '<span style="color: red;">' + result + '</span>');
                }
            }, 'text');
        });

        $('#fname').change(function () {
            if ($(this).val() === '') {
                $('#fname_valid_cell').html(error_img + '<span style="color: red;">Name Cannot Be Blank</span>');
            } else {
                $('#fname_valid_cell').html(ok_img);
            }
        });

        $('.name').change(function () {
            if ($(this).val() === '') {
                $('#' + this.id + '_valid_cell').html(error_img + '<span style="color: red;">Name Cannot Be Blank</span>');
            } else {
                $('#' + this.id + '_valid_cell').html(ok_img);
            }
        });

        $('#password').change(function () {
            if ($(this).val() === '') {
                $('#password_valid_cell').html(error_img + '<span style="color: red;">Password Cannot Be Blank</span>');
            } else if ($(this).val().length < 6) {
                $('#password_valid_cell').html(error_img + '<span style="color: red;">Password Must Be At Least 6 Characters</span>');
                $('#confirm').trigger('change');
            } else {
                $('#password_valid_cell').html(ok_img);
                $('#confirm').trigger('change');
            }
        });

        $('#confirm').change(function () {
            if ($(this).val() === $('#password').val() && $(this).val !== '') {
                $('#confirm_valid_cell').html(ok_img);
            } else {
                $('#confirm_valid_cell').html(error_img + '<span style="color: red;">Does Not Match Password</span>');
            }
        });

    });

</script>
</body>
</html>