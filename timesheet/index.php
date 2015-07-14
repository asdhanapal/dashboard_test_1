<?php
session_start();
session_destroy();
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Time sheet for P2RQA</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <script src="js/modernizr.js"></script>
    </head>
    <body>

        <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <center><!--<img src="img/amazon2.jpg">-->
                    <h5><b>Publisher to Reader Operations</b></h5>
                    </center>
                </div>
            </div>
        </div>
        
<!--        <div class="row">
            <div class="large-12 columns">
                    <center>
                <div data-alert class="alert-box warning round">
  If you want to modify data for before Apr 2015, Please <a href="http://localhost/old_versions/timesheet_old/">click here.</a> links expires on 03/Apr/2015 EOD.
  <a href="#" class="close">&times;</a>
</div>
                    </center>
                </div>
            </div>
        -->

        <div class="row" id="login_part">
            <div class="large-12 columns">
                <div class="panel">
                    <center>
                        <div class="large-4 medium-4 columns_center">
                            <center><h3>LOG IN</h3></center>
                            <!-- Login grid started -->
                            <hr>
                            <span id="err_msg1">Welcome</span>
                            <div class="row">
                                <div class="callout panel">
                                    <form>
                                        <div class="row collapse">

                                            <div class="large-12 columns" align="left" >
                                                User name:<br>&nbsp;
                                            </div>

                                            <div class="large-12 columns_center">
                                                <div class="row collapse">
                                                    <div class="small-8 columns">
                                                        <input type="text" placeholder="User name" name="u_name" id="u_name"/>
                                                    </div>
                                                    <div class="small-4 columns">
                                                        <span class="postfix">@amazon.com</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="large-12 columns" align="left">
                                                Password:<br>&nbsp;
                                            </div>
                                            <div class="large-12 columns_center">
                                                <div class="row collapse">
                                                    <div class="small-12 columns">
                                                        <input type="password" placeholder="Password" name="pass" id="pass"/>
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="large-12 columns">
                                                <input type="submit" class="small radius button" value="Log in" onclick="return login();">
                                            </div>
                                           <div class="large-12 columns">
                                               <div class="row">
                                                   <div class="large-6 columns"><a href="#" class="medium secondary button" onclick="show_fp();" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Forgot Password?</a></div>
                                                   <div class="large-6 columns"><a href="#" onclick="show_reg();" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Register Here</a></div>
                                               </div>
                                            </div>
                                        </div>       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </center>            
                    <hr>
                </div>
            </div>
        </div>
        
        <div class="row" id="registration_part">
            <div class="large-12 columns">
                <div class="panel">
                    <center>
                        <div class="large-4 columns_center">
                            <center><h3>Registration</h3></center>
                            <!-- Login grid started -->
                            <hr>
                            <span id="err_msg2">&nbsp;</span>
                            <div class="row">
                                <div class="callout panel">
                                    <form>
                                        <div class="row collapse">

                                            <div class="large-12 medium-12 columns" align="left" >
                                                User name:<br>&nbsp;
                                            </div>

                                            <div class="large-10 medium-10 columns_center">
                                                <div class="row collapse">
                                                    <div class="small-8 columns">
                                                        <input type="text" placeholder="User name" name="reg_u_name" id="reg_u_name"/>
                                                    </div>
                                                    <div class="small-4 columns">
                                                        <span class="postfix">@amazon.com</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="large-12 medium-12 columns" align="left">
                                                Select team:<br>&nbsp;
                                            </div>
                                            <div class="large-10 medium-10 columns_center">
                                                <div class="row collapse">
                                                    <div class="small-12 columns">
                                                        <select id="team" multiple="3">
                                                    <option> Loading...</option>
                                                </select>
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="large-12 columns">
                                                <input type="submit" class="small radius button" value="Register" onclick="return do_register();">
                                            </div>
                                           <div class="small-12 columns">
                                               <a href="#" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;" onclick="show_login();">Login</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="show_fp();" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Forgot password?</a>
                                            </div>
                                        </div>       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </center>            
                    <hr>
                </div>
            </div>
        </div>
        
        
        
        
           <div class="row" id="fp_part">
            <div class="large-12 columns">
                <div class="panel">
                    <center>
                        <div class="large-4 columns_center">
                            <center><h3>Having trouble signing in?</h3></center>
                            <!-- Login grid started -->
                            <hr>
                            <span id="err_msg3">&nbsp;</span>
                            <div class="row">
                                <div class="callout panel">
                                    <form>
                                        <div class="row collapse">

                                            <div class="large-12 medium-12 columns" align="left" >
                                                User name:<br>&nbsp;
                                            </div>

                                            <div class="large-10 medium-10 columns_center">
                                                <div class="row collapse">
                                                    <div class="small-8 columns">
                                                        <input type="text" placeholder="User name" name="fp_name" id="fp_name"/>
                                                    </div>
                                                    <div class="small-4 columns">
                                                        <span class="postfix">@amazon.com</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="large-12 columns">
                                                <input type="submit" class="small radius button" value="Get password" onclick="return fp();">
                                            </div>
                                           <div class="small-12 columns">
                                               <a href="#" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;" onclick="show_login();">Login</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="show_reg();" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Register Here</a>
                                            </div>
                                        </div>       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </center>            
                    <hr>
                </div>
            </div>
        </div>
        
        
        
        <script src="js/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
// foundation startup
$(document).foundation();

show_login();
$('#u_name').focus();

function login()
{
    var uname = $('#u_name').val();
    var pass = $('#pass').val();
    if(uname=="")
    {
        $('#err_msg1').html("Enter user name!");
        return false;
    }
    if(pass=="")
    {
        $('#err_msg1').html("Enter password!");
        return false;
    }
    $('#err_msg1').html('<img src="./img/loading.gif">');
    $.ajax({
        type: "POST",
//                dataType: "json",
        url: "./admin/check_login.php?action=login",
        data: "uname=" + uname + "&pass=" + pass,
        success: function(msg)
        {
            var html = $.trim(msg);
            if (html == 'U')
            {
                $('#err_msg1').html("<font color='green'>Redirecting...</font>");
                location.href = "./user/add_tasks.php";
            }
            else if (html == 'S') //SDA
            {
                $('#err_msg1').html("<font color='green'>Redirecting...</font>");
                location.href = "./sda/reports.php";
            }
            else if (html == 'M') //Manager
            {
                $('#err_msg1').html("<font color='green'>Redirecting...</font>");
                location.href = "./manager/reports.php";
            }
            else if (html == 'A') //Admin
            {
                $('#err_msg1').html("<font color='green'>Redirecting...</font>");
                location.href = "./admin/reports.php";
            }
            else
            {
                $('#err_msg1').html(html);
            }
        }
    });
    return false;
}

function do_register()
{
    $('#err_msg2').html('<img src="./img/loading.gif">');
    var uname = $('#reg_u_name').val();
    var teams = $('#team').val();
    if(uname=="")
    {
        $('#err_msg2').html("Enter user name!");
        return false;
    }
    if(teams==null)
    {
        $('#err_msg2').html("Select atleast one team!");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "./admin/do_reg.php?action=login",
        data: "uname=" + uname + "&&teams=" + teams,
        success: function(msg)
        {
            var html = $.trim(msg);
            $('#err_msg2').html(html);
        }
    });
    return false;
}

function fp()
{
    $('#err_msg3').html('<img src="./img/loading.gif">');
    var uname = $('#fp_name').val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "./admin/forgot_password.php?action=login",
        data: "uname=" + uname,
        success: function(msg)
        {
            if(msg[0]=='F')
            {
                $("#err_msg3").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
            }
            else
            {
                $("#err_msg3").hide().html("<font color=#259B47'>"+msg[1]+"</font>").fadeIn('slow');
            }
        }
    });
    return false;
}

function load_teams()
{
    $.ajax({
        type: "POST",
        url: "./admin/load_only_teams.php",
        success: function(msg)
        {
            var html = $.trim(msg);
            $("#team").html(html);
        }
    });
}

function show_login()
{
    $("#registration_part").hide();
    $("#fp_part").hide();
    $("#login_part").fadeIn();
}

function show_reg()
{
   $("#fp_part").hide();
   $("#login_part").hide();
   $("#registration_part").fadeIn();
   load_teams();
}

function show_fp()
{
    $("#fp_part").fadeIn();
    $("#registration_part").hide();
    $("#login_part").hide();
}
</script>
</body>
</html>
