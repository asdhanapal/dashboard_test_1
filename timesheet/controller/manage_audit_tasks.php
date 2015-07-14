<?php
include_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();
?>
<script src="../js/jquery.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/foundation_1.css" />
<script src="../js/modernizr.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/foundation.min.js"></script> 
<link rel="stylesheet" href="../css/styles.css">

<span class="temp_header"></span>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
        <center><span class="msgs"></span></center>
        <form id="form_audit" data-abide>
            <div class="row">
                <div class="large-3 columns">&nbsp;</div>
                <div class="large-2 columns">
                    <label>Team
                        <select id="team" onchange="filter_1();" required>
                            <option value=""> Loading...</option>
                        </select>
                        <small class="error">Team is required!</small></label>
                </div>
                <div class="large-2 columns">
                    <label>Audit task
                        <input type="text" id="task" value="" placeholder="Task" required>
                        <small class="error">Build is required!</small></label>
                </div>
                
                <div class="large-1 columns">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" class="tiny button" value="Submit" onclick="add_audit_task();" name="submit">
                    </label>
                </div>
                <div class="large-4 columns">&nbsp;</div>
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
        url: "../sda/load_teams.php",
        success: function (msg)
        {
            var html = $.trim(msg);
            $("#team").html(html);
        }
    });
}
function filter_1()
{
    var team = $('#team').val();
    $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
    $("#user_tasks_list").load("../controller/load_exist_audit_tasks.php", { "team": team}, function (response, status, xhr) {
        if (status == "success")
        {
            $('#loading_user_data').html('');
            $("#user_tasks_list").show('slow');
        }
    });
}

 function add_audit_task()
    {
        var team=$('#team').val();
        var task=$('#task').val();
        if(team=="" || task=="" )
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        var data={task:task,team:team};
        $.ajax({
            type: "POST",
            url: "../controller/add_audit_task.php",
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
            }
        });
        return false;
    }
    
    function delete_audit_task()
    {
        alert("Unable to delete!");
        return false;
    }
</script>

<style>
.temp_header
{
    margin-top: 20px;
    display:inline-block; /*or display:block;*/
}
</style>
