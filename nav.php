<?php
$isadmin = true;

?>
<div class="navbar navbar-default" role="navigation">
    <nav>

    <?php
    if ($isadmin) {
        echo '<ul class="nav navbar-nav">
                <li class="navbar-brand navbar-header">Admin Menu:</li>
                <li class="',(basename($_SERVER['PHP_SELF']) == 'addChambers.php')?' active':'','"><a href="/greenhouse/admin/addChambers.php">Create Chambers</a></li>
                <li class="',(basename($_SERVER['PHP_SELF']) == 'addSettings.php')?' active':'','"><a href="/greenhouse/admin/addSettings.php">Create Settings</a></li>
                <li class="',(basename($_SERVER['PHP_SELF']) == 'addPeriods.php')?' active':'','"><a href="/greenhouse/admin/addPeriods.php">Create Growth Period</a></li>
                <li class="',(basename($_SERVER['PHP_SELF']) == 'addEvents.php')?' active':'','"><a href="/greenhouse/admin/addEvents.php">Manage Events/Notices</a></li>
                <li class="',(basename($_SERVER['PHP_SELF']) == 'manageLabs.php')?' active':'','"><a href="/greenhouse/admin/manageLabs.php">Manage Lab Catagories</a></li></ul>';
    }
    ?>
        <ul class="nav navbar-nav">
        <li class="navbar-brand navbar-header">Menu:</li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php')?' active':''; ?>"><a href='/greenhouse/home.php'>View Current Chamber Settings</a></li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'viewPlants.php')?' active':''; ?>"><a href='/greenhouse/viewPlants.php'>Reserve Space</a></li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'reservations.php')?' active':''; ?>"><a href='/greenhouse/reservations.php'>See Current Reservations <br /> And Print Labes</a></li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'upcomingSettings.php')?' active':''; ?>"><a href='/greenhouse/upcomingSettings.php'>View Upcoming Growth Periods</a></li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php')?' active':''; ?>"><a href='/greenhouse/profile.php'>Edit Profile And Labs</a></li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php')?' logout.php':''; ?>"><a href='/greenhouse/logout.php'>Log Out</a></li>
        </ul>
    </nav>
</div>
