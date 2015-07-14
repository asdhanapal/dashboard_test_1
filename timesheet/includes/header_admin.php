<?php
session_start();

if(isset($_SESSION) && empty($_SESSION) || $_SESSION['admin_id']=="") {
   header("location:../"); }
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Time sheet for P2RQA</title>
        <link rel="stylesheet" href="../css/foundation_1.css" />
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
                        <div class="large-10 columns">
                            <h5><b>Publisher to Reader Operations</b></h5>
                        </div>
                        <div class="large-2 columns">
                            Welcome <?php echo $_SESSION['admin_name']; ?>
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
                        <li <?php echo $current_page=="dashboard.php"?"class='active'":"";?>><a href='dashboard.php'><span>Dashboard</span></a></li>
                        <li <?php echo $current_page=="dashboard_2.php"?"class='active'":"";?>><a href='dashboard_2.php'><span>Dashboard 2</span></a></li>
                        <li <?php echo $current_page=="reports.php"?"class='active'":"";?>><a href='reports.php'><span>Report</span></a></li>
                        <li <?php echo $current_page=="ot_reports.php"?"class='active'":"";?>><a href='ot_reports.php'><span>OT Report</span></a></li>
                        <li <?php echo $current_page=="chart.php"?"class='active'":"";?>><a href='chart.php'><span>Trend Report</span></a></li>
                        <li <?php echo $current_page=="con_fac.php"?"class='active'":"";?>><a href='con_fac.php'><span>Daily target</span></a></li>
                        <li <?php echo $current_page=="teams.php"?"class='active'":"";?>><a href='teams.php'><span>Manage Teams/Tasks</span></a></li>
                        <li <?php echo $current_page=="user_manage.php"?"class='active'":"";?>><a href='user_manage.php'><span>User management</span></a></li>
                       <!-- <li><a href='#'><span>Time Sheet Audit</span></a></li>-->
                       <!-- <li><a href='#'><span>Holidays</span></a></li>-->
                        <li <?php echo $current_page=="profile.php"?"class='active'":"";?>><a href='profile.php'><span>Change profile</span></a></li>
                        <li class='last'><a href='../includes/logout.php'><span>Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
