<?php
session_start();
//print_r($_SESSION);
if(isset($_SESSION) && empty($_SESSION) || $_SESSION['sda_id']=="") {
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

        
        
               <div class="fixed">
<nav class="top-bar" data-topbar role="navigation">
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">Publisher to Reader Operations</a></h1>
    </li>
<!--      Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone 
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>-->
  </ul>

  <section class="top-bar-section">
     Right Nav Section 
    <ul class="right">
      <li class="active"><a href="#">Right Button Active</a></li>
      <li class="has-dropdown">
        <a href="#">Right Button Dropdown</a>
        <ul class="dropdown">
          <li><a href="#">First link in dropdown</a></li>
          <li class="active"><a href="#">Active link in dropdown</a></li>
        </ul>
      </li>
    </ul>

     Left Nav Section 
    <ul class="left">
      <li><a href="#">Left Nav Button</a></li>
      <li class="has-dropdown">
        <a href="#">Right Button Dropdown</a>
        <ul class="dropdown">
          <li><a href="#">First link in dropdown</a></li>
          <li class="active"><a href="#">Active link in dropdown</a></li>
        </ul>
      </li>
    </ul>
  </section>
</nav>
        </div>        
        
        
        
        
        
        
        
        <!-- header image-->
             <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <div class="row">
                        <div class="large-6 columns">
<!--                            <img src="../img/amazon-logo.gif">-->
                            <h5><b>Publisher to Reader Operations</b></h5>
                        </div>
                        <div class="large-4 columns" id="remain_hrs" style="color: red; text-decoration: blink;">
                        </div>
                        <div class="large-2 columns">
                            Welcome <?php echo $_SESSION['sda_first_name']; ?>
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
                        <li <?php echo $current_page=="your_reports.php"?"class='active'":"";?>><a href='your_reports.php'><span>MY Entries</span></a></li>
                        <li <?php echo $current_page=="con_fac.php"?"class='active'":"";?>><a href='con_fac.php'><span>Daily target</span></a></li>
                        <li <?php echo $current_page=="reports.php"?"class='active'":"";?>><a href='reports.php'><span>Report</span></a></li>
                        <li <?php echo $current_page=="chart.php"?"class='active'":"";?>><a href='chart.php'><span>Trend view</span></a></li>
                        <li <?php echo $current_page=="teams.php"?"class='active'":"";?>><a href='teams.php'><span>Manage Teams/Tasks</span></a></li>
                        <li <?php echo $current_page=="user_manage.php"?"class='active'":"";?>><a href='user_manage.php'><span>User management</span></a></li>
                        <li <?php echo $current_page=="manage_build.php"?"class='active'":"";?>><a href='manage_build.php'><span>Build management</span></a></li>
                        <li <?php echo $current_page=="mapping_builds.php"?"class='active'":"";?>><a href='mapping_builds.php'><span>Map builds</span></a></li>
                        <li <?php echo $current_page=="audit.php"?"class='active'":"";?>><a href='audit.php'><span>Audit</span></a></li>
                        <li <?php echo $current_page=="tc_addition.php"?"class='active'":"";?>><a href='tc_addition.php'><span>TC Addition</span></a></li>
                        <!--<li><a href='#'><span>Holidays</span></a></li>-->
                        <li <?php echo $current_page=="profile.php"?"class='active'":"";?>><a href='profile.php'><span>Change profile</span></a></li>
                        <li class='last'><a href='../includes/logout.php'><span>Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
