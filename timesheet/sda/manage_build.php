<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="custom_panel_1">

            <div class="row">
                <div class="large-12 columns">
                    <center><span class="msgs"></span></center>
                </div>
            </div>

            <div class="row">
                <div class="large-6 columns">
                    <form  data-abide>
                        <fieldset>
                            <legend>Builds</legend>
                            <div class="row">
                                <div class="large-4 columns">
                                    <select id="team_team" required onchange="filter_team();">
                                        <option> Loading...</option>
                                    </select>
                                    <small class="error">Team is required!</small>
                                </div>
                                <div class="large-4 columns">
                                    <input type="text" name="build_name" id="build_name" placeholder="Build Name" required="">
                                    <small class="error">Build name is required!</small>
                                </div>
                                <div class="large-4 columns">
                                    <label>
                                        <input type="button" class="tiny button" value="ADD BUILD" onclick="add_build();" name="submit">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-4 large-offset-8 columns end text-right">
                                    <a href="#" onclick="filter_team();">View Builds</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <div class="large-6 columns">
                    <form data-abide>
                        <fieldset>
                            <legend>Release</legend>
                            <div class="row">
                                <div class="large-4 columns">
                                    <select id="team_release" required onchange="filter_release();">
                                        <option> Loading...</option>
                                    </select>
                                    <small class="error">Team is required!</small>
                                </div>
                                <div class="large-4 columns">
                                    <input type="text" name="release_name" id="release_name" placeholder="Release Name" required="">
                                    <small class="error">Release name is required!</small>
                                </div>
                                <div class="large-4 columns">
                                    <label>
                                        <input type="button" class="tiny button" value="ADD RELEASE" onclick="add_release();" name="submit">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-4 large-offset-8 columns end text-right">
                                    <a href="#" onclick="filter_release();">View Releases</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div class="callout panel" style="padding: 0px 0px 20px 20px;">
            <span id="loading_user_data">&nbsp;&nbsp;</span>
            <div id="user_tasks_list" style="height: 400px; overflow-y: auto;"></div>
        </div>
    </div>
</div>

<script>
   $(function() {
        load_teams();
   });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#team_team").html(html);
                $("#team_release").html(html);
                filter_team();
            }
        });
    }

    function filter_team()
    {
        var team=$('#team_team').val();
        var build=$('#build_name').val();
        $("#user_tasks_list").load("../controller/load_builds.php", {"team": team,"build":build}, function(response, status, xhr) {
            if (status == "success")
            {
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function filter_release()
    {
        var team=$('#team_release').val();
        var build=$('#release_name').val();
        $("#user_tasks_list").load("../controller/load_releases.php", {"team": team,"release":build}, function(response, status, xhr) {
            if (status == "success")
            {
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function add_build()
    {
        var team=$('#team_team').val();
        var build=$('#build_name').val();
        if(team=="" || build=="")
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        $.ajax({
            type: "POST",
            url: "../controller/add_build.php",
            dataType: "json", 
            data: "team=" + team+"&build=" + build,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    filter_team();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    filter_team();
                    $(".msgs").hide().html("<font color=#41A868'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    function add_release()
    {
        var team=$('#team_release').val();
        var release=$('#release_name').val();
        if(team=="" || release=="")
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        $.ajax({
            type: "POST",
            url: "../controller/add_release.php",
            dataType: "json", 
            data: "team=" + team+"&release=" + release,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    filter_release();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    filter_release();
                    $(".msgs").hide().html("<font color=#41A868'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    function change_status(id,status)
    {
         if(confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "../controller/build_status_change.php?action=change_build_status",
                data: "id="+id+"&&status="+status,
                success: function(msg)
                {
                    var html = $.trim(msg);
                    $('#loading_user_data').html("<center>"+msg+"</center>").hide();
                    $('#loading_user_data').fadeIn("slow");
                    $('#loading_user_data').delay(5000).fadeOut("slow");
                    filter_team();
                }
            });
            return false;
        }
    }
    
    function delete_release(id)
    {
         if(confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "../controller/delete_release.php?action=del_rel",
                data: "id="+id,
                success: function(msg)
                {
                    var html = $.trim(msg);
                    $('#loading_user_data').html("<center>"+msg+"</center>").hide();
                    $('#loading_user_data').fadeIn("slow");
                    $('#loading_user_data').delay(5000).fadeOut("slow");
                    filter_release();
                }
            });
            return false;
        }
    }
</script>
<?php
include_once '../includes/footer.php';
?>