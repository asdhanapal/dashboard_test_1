<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
//print_r($_SESSION);
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
                    <select id="team" onchange="load_task();">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" onchange="update_sub_task(this.value); load_task_desc(this.value);">
                        <option value=""> -- Pls select team first --</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>Sub Task</label>
                    <select id="sub_task">
                        <option value=""> -- Select task first --</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>Task desc</label>
                    <select id="task_desc">
                        <option value=""> -- Select task first --</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="large-3 large-offset-9 columns" style="text-align: right;">
                    <center>
                    <label>&nbsp;</label>
                    <input type="button" class="small radius button" value="Working hrs entries" onclick="filter_1();">
                    <input type="button" class="small radius button" value="OT entries" onclick="filter_2();">
                    </center>
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
//    $(document).ready(function(){
//       filter_1(); 
//    });
    
    $(function() {
        load_teams();
        filter_1();
    });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_only_teams_with_select_all.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
            }
        });
    }

    function load_task()
    {
        var id = $("#team").val();
        $.ajax({
            type: "POST",
            url: "./load_tasks.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task").html(html);
            }
        });
    }
    
    function update_sub_task(task_id)
    {
        $.ajax({
            type: "POST",
            url: "./load_sub_tasks.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#sub_task").html(html);
            }
        });
    }
    
    function load_task_desc(task_id)
    {
        $.ajax({
            type: "POST",
            url: "./load_task_desc.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task_desc").html(html);
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
        $("#user_tasks_list").load("./load_user_all_entries.php", {"date_from": date_from,"date_to":date_to,"team":team,"task":task,"s_task":s_task,"task_desc":task_desc,"user":user}, function(response, status, xhr) {
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
        $("#user_tasks_list").load("./load_user_all_ot_entries.php", {"date_from": date_from,"date_to":date_to,"team":team,"task":task,"s_task":s_task,"task_desc":task_desc,"user":user}, function(response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
</script>

<?php
include_once '../includes/footer.php';
?>
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


</script>
<!-- Date picker files and function end-->
<script>
//$(document).foundation();
//var xyz = 1;
</script>

