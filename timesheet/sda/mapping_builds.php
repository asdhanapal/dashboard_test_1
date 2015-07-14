<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <center><span class="msgs"></span></center>
            <form id="form_mapping_builds" data-abide onsubmit="return add_connection();">
            <div class="row">
                <div class="large-9 columns">
                    <div class="row">
                        <div class="large-2 columns">
                            <label>Date
                                <input type="text" id="date" value="" onchange="filter_1();" required><small class="error">Date is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Team
                                <select id="team" onchange="load_builds(this.value); load_releases(this.value); filter_1();"  required>
                                    <option value=""> Loading...</option>
                                </select><small class="error">Team is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Build
                                <select id="builds"  required>
                                    <option value="" disabled=""> -- Select team first --</option>
                                </select><small class="error">Build is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Release
                                <select id="release"  required>
                                    <option value="" disabled=""> -- Select team first --</option>
                                </select><small class="error">Release is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Build Type
                                <select id="type"  required>
                                    <option value="" disabled="" selected=""> -- Select --</option>
                                    <option value="0">Offical</option>
                                    <option value="1">Non-Offical</option>
                                </select><small class="error">Build type is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Run status
                                <select id="status"  required>
                                    <option value="" disabled="" selected=""> -- Select --</option>
                                    <option value="0">Partial</option>
                                    <option value="1">Complete</option>
                                </select><small class="error">Run status is required!</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="large-3 columns">
                    <div class="row">
                        <div class="large-7 columns">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="tiny button" value="Add connection" name="submit" id="submit_1">
                            <input type="Reset" class="tiny button" value="Reset" name="reset" id="reset"  onmouseout="filter_1();" title="Move the mouse out to view all records." data-tooltip aria-haspopup="true" class="tip-top">
                        </label>
                        </div>
                        <div class="large-5 columns">
                            <label>
                                <a href="./manage_build.php">Manage build</a><br><br>
                                <a href="#" onclick="show_msg();">Unmapped builds</a>
                            </label><br>
                            
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div class="callout panel" style="padding: 20px 2px 20px 20px;">
            <span id="loading_user_data"></span>
            <div id="user_tasks_list" style="height: 400px; overflow-y: auto;"></div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        load_teams();
        filter_1();
    });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
            }
        });
    }

    function load_builds(id)
    {
        $.ajax({
            type: "POST",
            url: "../controller/load_builds_mapping.php",
            data: "team_id=" + id,
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#builds").html(html);
            }
        });
    }
    
    function load_releases(id)
    {
        $.ajax({
            type: "POST",
            url: "../controller/load_releases_mapping.php",
            data: "team_id=" + id,
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#release").html(html);
            }
        });
    }

    function add_connection()
    {

        var date=$('#date').val();
        var team=$('#team').val();
        var build=$('#builds').val();
        var release=$('#release').val();
        var type=$('#type').val();
        var status=$('#status').val();
        if(date=="" || team=="" || build=="" || release=="" || type=="" || status=="")
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        
        var data={date:date,team:team,build:build,release:release,type:type,status:status}
        $.ajax({
            type: "POST",
            url: "../controller/add_connection.php",
            dataType: "json", 
            data: data,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    filter_1();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    filter_1();
                    $(".msgs").hide().html("<font color=#41A868'>"+msg[1]+"</font>").fadeIn('slow');
                }
                load_builds(team);
            }
        });
        return false;
    }
    
    function filter_1()
    {
        var date = $('#date').val();
        var team = $('#team').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("../controller/load_mapped_connections.php", { "date": date, "team": team}, function (response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function delete_connection(id)
    {
         if(confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "../controller/delete_mapping.php?action=del_rel",
                data: "id="+id,
                success: function(msg)
                {
                    var html = $.trim(msg);
                    $('.msgs').html("<center>"+msg+"</center>");
                    filter_1();
                }
            });
            return false;
        }
    }
    
    function show_msg()
    {
        alert('Build field always shown the unmapped builds only!');
        $('.msgs').html('<font color=red>Build field always shown the unmapped builds only!');
        $( "#builds" ).focus();
        return false;
    }
</script>

<?php
include_once '../includes/footer.php';
?>

<!-- Date picker files and function start-->
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
    $(function () {
        $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: "yy-mm-dd"
        });
    });
</script>
<!-- Date picker files and function end-->