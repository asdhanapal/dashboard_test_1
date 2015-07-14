<?php
include_once '../includes/header_admin.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <center><span class="msgs"></span></center>
            <div class="row">
                <div class="large-2 columns">
                    <label>From</label>
                    <input type="text" id="date_from" value="<?php echo date('Y-m', $_SERVER['REQUEST_TIME'])."-01";?>">
                </div>
                <div class="large-2 columns">
                    <label>To</label>
                    <input type="text" id="date_to" value="<?php echo date('Y-m-d', $_SERVER['REQUEST_TIME']);?>">
                </div>
                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" onchange="load_task();" placeholder=" -- Teams --" multiple="multiple">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" onchange="update_sub_task(this.value); load_task_desc(this.value);" multiple="multiple" placeholder=" -- Tasks --">
                        <option value="" disabled=""> -- Pls select team first --</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <label>Sub Task</label>
                    <select id="sub_task" multiple="multiple" placeholder=" -- Sub tasks --">
                        <option value="" disabled=""> -- Select task first --</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <label>Task desc</label>
                    <select id="task_desc" multiple="multiple" placeholder=" -- Task desc. --">
                        <option value="" disabled=""> -- Select task first --</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="large-2 columns">
                    <label>User</label>
                    <select id="user"  multiple="multiple" placeholder=" -- Users --">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <label>OT values</label>
                    <select id="ot_status" disabled="" >
                        <option value="0">Working hrs only</option>
                        <option value="1">OT hrs only</option>
                        <option value="2" selected>All</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <label>&nbsp;</label>
                </div>
                <div class="large-2 columns">
                    <center>
                        <label>&nbsp;</label>
                <!--    <a href="#"><br>+ Modify report format</a>-->
                    </center>
                </div>
                <div class="large-3 columns">
                    <center>
                    <label>&nbsp;</label>
                    <input type="button" class="small radius button" value="Raw data details" onclick="filter_1();">&nbsp;<!--data-tooltip aria-haspopup="true" class="tip-left" title="For getting the raw data details."-->
                    <input type="button" class="small radius button" value="Productivity report" onclick="filter_2();" >
                    </center>
                </div>
                <div class="large-1 columns">
                <label>&nbsp;</label>
                <input type="button" class="small radius button" value="Reset" disabled="">
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
        
    </div>
</div>

<script>
$(document).ready(function () {
    load_teams();
    load_users();
    $('#task').SumoSelect();
    $('#sub_task').SumoSelect();
    $('#task_desc').SumoSelect();
    $('#user').SumoSelect();
    $('#ot_status').SumoSelect();
//    window.asd = $('.SlectBox').SumoSelect({ csvDispCount: 3 });
//    window.test = $('.testsel').SumoSelect({okCancelInMulti:true });
    filter_1();
});

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams_all.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
                $('#team').SumoSelect();
//                load_task();
            }
        });
    }

    function load_task()
    {
        var id = $("#team").val();
        $.ajax({
            type: "POST",
            url: "./load_tasks_multiple_teams.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $('#task')[0].sumo.unload();
                $("#task").html(html);
                $('#task').SumoSelect();
            }
        });
    }
    
    function update_sub_task()
    {
        var task_id = $("#task").val();
        $.ajax({
            type: "POST",
            url: "./load_sub_tasks_multiple_tasks.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $('#sub_task')[0].sumo.unload();
                $("#sub_task").html(html);
                $('#sub_task').SumoSelect();
            }
        });
    }
    
    function load_task_desc()
    {
        var task_id = $("#task").val();
        $.ajax({
            type: "POST",
            url: "./load_task_desc.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $('#task_desc')[0].sumo.unload();
                $("#task_desc").html(html);
                $('#task_desc').SumoSelect();
            }
        });
    }

 function load_users()
    {
        $.ajax({
            type: "POST",
            url: "./load_users_with_sep.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $('#user')[0].sumo.unload();
                $("#user").html(html);
                $('#user').SumoSelect();
            }
        });
    }

    function filter_1()
    {
        $("#user_tasks_list").hide();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var team=$('#team').val();
        var task=$('#task').val();
        var s_task=$('#sub_task').val();
        var task_desc=$('#task_desc').val();
        var user=$('#user').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("./load_user_entries.php", {"date_from": date_from,"date_to":date_to,"team":team,"task":task,"s_task":s_task,"task_desc":task_desc,"user":user}, function(response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function filter_2()
    {
        $("#user_tasks_list").hide();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var team=$('#team').val();
        var task=$('#task').val();
        var s_task=$('#sub_task').val();
        var task_desc=$('#task_desc').val();
        var user=$('#user').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("./load_user_entries_2.php", {"date_from": date_from,"date_to":date_to,"team":team,"task":task,"s_task":s_task,"task_desc":task_desc,"user":user}, function(response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    //$('#error_msg').delay(5000).fadeOut("slow");  
</script>

<?php
include_once '../includes/footer.php';
?>


<script src="../js/jquery.sumoselect.js"></script>
<link href="../css/sumoselect.css" rel="stylesheet" />
<style type="text/css">
        p,div,ul,li{padding:0px; margin:0px;}
    </style>

<!-- Date picker files and function start-->
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
   $(function() {
    $( "#date_from" ).datepicker({
      changeMonth: true,
      changeYear:true,
      dateFormat:"yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#date_to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#date_to" ).datepicker({
      changeMonth: true,
      changeYear:true,
      dateFormat:"yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });

load_users();
</script>
<!-- Date picker files and function end-->