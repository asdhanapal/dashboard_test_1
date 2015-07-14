<?php
session_start();
if(isset($_SESSION) && empty($_SESSION)) {
   header("location:../"); }
   
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Time sheet for P2RQA</title>
        <link rel="stylesheet" href="../css/foundation.css" />
        <script src="../js/modernizr.js"></script>
        <script src="../js/jquery.js"></script>
        <script src="../js/foundation.min.js"></script>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <!-- header image-->
        <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <div class="row">
                        <div class="large-6 columns">
                            <!--<img src="../img/amazon-logo.gif">-->
                            <h5><b>Publisher to Reader Operations</b></h5>
                        </div>
                        <div class="large-4 columns" id="remain_hrs" style="color: red; text-decoration: blink;">
                        </div>
                        <div class="large-2 columns">
                            Welcome <?php echo $_SESSION['user_name']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu-->
        <div class="row">
            <div class="large-12 columns">
                <div id='cssmenu'>
                    <ul>
                        <?php $current_page=substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1)?>
                        <li <?php echo $current_page=="add_tasks.php"?"class='active'":"";?>><a href='add_tasks.php'><span>Add tasks</span></a></li>
                        <li <?php echo $current_page=="add_ot.php"?"class='active'":"";?>><a href='add_ot.php'><span>Add OT</span></a></li><!---->
                        <li <?php echo $current_page=="reports.php"?"class='active'":"";?>><a href='reports.php'><span>Weekly/Monthly Reports</span></a></li>
                        <li <?php echo $current_page=="profile.php"?"class='active'":"";?>><a href='profile.php'><span>Change profile</span></a></li>
                        <li class='last'><a href='../includes/logout.php'><span>Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
