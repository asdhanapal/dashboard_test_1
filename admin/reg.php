
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
                            <center><h3>Registration</h3></center>
                            <!-- Login grid started -->
                            <hr>
                            <span id="err_msg">&nbsp;</span>
                            <div class="row">
                                <div class="callout panel">
                                    <div class="row collapse">
                                        <div class="large-12 columns">
                                            <div class="small-4 columns">User name:</div>
                                            <div class="small-8 columns"><input type="text" placeholder="User name" name="u_name" id="u_name"/></div>

                                            <div class="small-4 columns">Select Team</div>
                                            <div class="small-8 columns"><select id="team" multiple="3">
                                                    <option> Loading...</option>
                                                </select></div>
                                        </div>
                                        <div class="large-12 columns">
                                            <input type="submit" class="small radius button" value="Register" onclick="return do_register();">
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