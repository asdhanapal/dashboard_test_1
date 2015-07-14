
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Time sheet for P2RQA</title>
        <link rel="stylesheet" href="../css/foundation.css" />
        <script src="../js/modernizr.js"></script>
    </head>
    <body>

        <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <center><img src="../img/amazon2.jpg"></center>
                </div>
            </div>
        </div>

        <div class="row" id="registeration_part">
            <div class="large-12 columns">
                <div class="panel">
                    <center>
                        <div class="large-4 columns_center">
                            <center><h3>Get a new password</h3></center>
                            <!-- Login grid started -->
                            <hr>
                            <span id="err_msg">&nbsp;</span>
                            <div class="row">
                                <div class="callout panel">
                                    <div class="row collapse">
                                        <div class="large-12 columns">
                                            <div class="small-4 columns">User mail:</div>
                                            <div class="small-8 columns"><input type="text" placeholder="User name" name="u_name" id="u_name"/></div>
                                        </div>
                                        <div class="large-12 columns">
                                            <input type="submit" class="small radius button" value="Get password" onclick="return do_register();">
                                        </div>
                                        

                                           <div class="small-12 columns">
                                                <a href="#" class="medium secondary button" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Login</a>
                                                &nbsp;New user?&nbsp;&nbsp;&nbsp;<a href="admin/reg.php" class="medium secondary button" target="_blank" style="padding-top: 0.500rem; padding-bottom: 0.500rem;">Register Here</a>
                                            </div>
                                    </div>       
                                </div>
                            </div>
                        </div>
                    </center>            
                    <hr>
                </div>
            </div>
        </div>
        <script src="../js/jquery.js"></script>
        <script src="../js/foundation.min.js"></script>
        <script>
// CSS style startup... foundation startup
                                                $(document).foundation();

                                                $(function() {
                                                    load_teams();
                                                });

                                                function load_teams()
                                                {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "./load_only_teams.php",
                                                        success: function(msg)
                                                        {
                                                            var html = $.trim(msg);
                                                            $("#team").html(html);
                                                        }
                                                    });
                                                }

                                                function do_register()
                                                {
                                                    $('#error_msg').html('<img src="../images/loading.gif">');
                                                    var uname = $('#u_name').val();
                                                    var teams = $('#team').val();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "do_reg.php?action=login",
                                                        data: "uname=" + uname + "&&teams=" + teams,
                                                        success: function(msg)
                                                        {
                                                            var html = $.trim(msg);
                                                            $('#err_msg').html(html);
                                                        }
                                                    });
                                                    return false;
                                                }

        </script>
    </body>
</html>