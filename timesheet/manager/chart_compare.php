<?php
include_once '../includes/header_1.php';
include_once './data_page.php';
//print_r($_SESSION);
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <form id="form_user_tasks" data-abide>
        <div class="panel">
<!--            <center><span class="msgs"></span></center>-->
            
<!--            <div class="row">
                <div class="large-5 columns">
                    <div class="row">
                        <div class="large-12 columns">
                            <label>Chart type</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-2 columns">
                            <label>&nbsp;</label>
                            <input type="radio" id="Pie" value="Pie">&nbsp;Pie
                        </div>
                        
                        <div class="large-2 columns">
                            <label>&nbsp;</label>
                            <input type="radio" id="line" value="Line">&nbsp;Line
                        </div>
                        
                        <div class="large-2 columns">
                            <label>&nbsp;</label>
                            <input type="radio" id="Column" value="Column">&nbsp;Column
                        </div>
                        <div class="large-6 columns"></div>
                    </div>
                </div>
                
                <div class="large-7 columns">
                    <div class="row">
                        <div class="large-4 columns">
                            <label>Chart title</label>
                            <input type="text" id="chart_title" >
                        </div>
                        
                        <div class="large-4 columns">
                            <label>X axis </label>
                            <input type="text" id="chart_title" >
                        </div>
                        
                        <div class="large-4 columns">
                            <label>Y axis</label>
                            <input type="text" id="chart_title" >
                        </div>
                    </div>
                </div>
            </div>-->

<!--            <hr>-->

            <div class="row">
                <div class="large-2 columns">
                    <label>From</label>
                    <input type="text" id="date_from" value="<?php echo date('Y-m-d',$_SERVER['REQUEST_TIME']);?>">
                    
                    
                    <input type="text" id="date_to" value="<?php echo date('Y-m-d',$_SERVER['REQUEST_TIME']);?>">
                </div>

                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" onchange=" load_tasks();" multiple="5">
                        <option> Loading...</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" multiple="5">
                        <option value="" disabled=""> -- Select team first --</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>User</label>
                    <select id="user"  multiple="5">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                <div class="large-2 columns" style="text-align: right;">
<!--                    <label>&nbsp;</label>
                    
                    <span>+</span>&nbsp;<a href="#" id="com_mod_link">compare</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                </div>
<!--                <div class="large-2 columns">
                    <center>
	                    <label>&nbsp;</label>
	                    <input type="button" class="small radius button" value="ADD" onclick="add_con_fac();">&nbsp;<input type="button" class="small radius button" value="Reset">
                </center>
                </div>-->
            </div>
            
<!--            <div class="row" id="compare_module"> 
                <div class="large-2 columns">
                    <label>From</label>
                    <input type="text" id="date_from" value="2014-10-20">
                </div>

                <div class="large-2 columns">
                    <label>To</label>
                    <input type="text" id="date_to" value="2014-10-20">
                </div>
                
                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" onchange=" load_tasks();" required>
                        <option> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
                
                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" onchange="update_sub_task(this.value);" required>
                        <option value=""> -- Select team first --</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                
                <div class="large-2 columns">
                    <label>User</label>
                    <select id="user">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                </div>
            </div>-->
        </div>
</form>

        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel">
                    <span id="loading_user_data">&nbsp;</span>
                    <div id="user_tasks_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#compare_module").hide();
    $(function() {
            load_teams();
            load_users();
            filter_1();
//            filter();
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
            }
        });
    }
    
    function load_tasks()
    {
        var id = $("#team").val();
        $.ajax({
            type: "POST",
            url: "./load_tasks_all.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task").html(html);
            }
        });
    }
    
    function load_users()
    {
        $.ajax({
            type: "POST",
            url: "./load_users_all.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#user").html(html);
            }
        });
    }
    
    function filter_1()
    {
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("../chart/pie-gradient/index.htm", {}, function(response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }

//    $("#com_mod_link").click(function(){
//    $("#compare_module").toggle(500,"swing");
//  });
  
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