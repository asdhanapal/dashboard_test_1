<?php
include_once '../includes/header.php';
include_once '../includes/date_picker.php';
print_r($_SESSION);
?>

<br>
<div class="row">
    <div class="large-12 columns">
        <form id="form_user_tasks" data-abide>
        <div class="panel">
            <center><span class="msgs"></span></center>
            
            <?php if($_SESSION['team_count']>1) { ?>
            <input type="hidden" name="judge" id="judge" value="1">
            <div class="row">
                
                <div class="small-2 columns">
                    <label>Select Date</label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select the date from the date picker">
                     <small class="error">Date is required</small>
                </div>
                
                <div class="small-1 columns">
                    <label>Select Team</label>
                    <select id="team" name="team" onchange="load_tasks();" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
                
                <div class="small-1 columns">
                    <label>Select Task</label>
                    <select id="task" name="task" onchange="load_sub_tasks(this.value);" required>
                        <option value="" disabled="">Select task</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                
                <div class="small-1 columns">
                    <label>Sub task</label>
                    <select id="sub_task" name="sub_task" required>
                        <option value="">Select sub task </option>
                    </select>
                    <small class="error">Sub task is required!</small>
                </div>
                
                <div class="small-1 columns" >
                    <span id="task_des_space">&nbsp;</span>
                    <div id="task_des">
                    <label>Task desc.</label>
                    <select id="task_desc" name="task_desc" required >
                        <option value=""> Select task desc</option>
                    </select>
                    <small class="error">Task desc is required!</small>
                    </div>
                </div>
                
                <div class="small-1 columns">
                    <label>Count</label>
                    <input type="text" id="qty" placeholder="Count" name="qty" required pattern="integer" data-tooltip aria-haspopup="true" class="tip-top" title="For example: 10"> <!--pattern="[001-1000]"-->
                    <small class="error">Invalid value! </small>
                </div>
                
                <div class="small-1 columns">
                    <label>Time</label>
                    <input type="text" id="time" placeholder="HH:MM" name="time" required pattern="[0-0][0-8]:[0-5][0-9]+" data-tooltip aria-haspopup="true" class="tip-top" title="For example 08:00">
                    <small class="error">Valid time required. </small>
                </div>
                
                <div class="small-2 columns">
                    <label>Comments</label>
                    <textarea placeholder="Comments" id="cmds" name="cmds" ></textarea>
                </div>
                
                <div class="small-2 columns">
                    <label>&nbsp;</label>
                    <input type="button" class="tiny button" value="Submit" onclick="add_task();" name="submit">
                    <input type="button" class="small radius button" value="Reset">
                </div>
                
            </div>
            <?php } else { ?>
            <input type="hidden" name="judge" id="judge" value="0">
                <input type="hidden" name="team" id="team" value="<?php echo $_SESSION['team_id'][0];?>">
            <div class="row">
                
                <div class="small-2 columns">
                    <label>Select Date</label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select the date from the date picker" value="">
                     <small class="error">Date is required</small>
                </div>
                
                <div class="small-1 columns">
                    <label>Select Task</label>
                    <select id="task" name="task" onchange="load_sub_tasks(this.value);" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                
                <div class="small-1 columns">
                    <label>Sub task</label>
                    <select id="sub_task" name="sub_task" required>
                        <option value="">Select task first</option>
                    </select>
                    <small class="error">Sub task is required!</small>
                </div>
                
                <div class="small-2 columns" >
                    <span id="task_des_space">&nbsp;</span>
                    <div id="task_des">
                    <label>Task desc.</label>
                    <select id="task_desc" name="task_desc" required >
                        <option value=""> Select task desc</option>
                    </select>
                    <small class="error">Task desc is required!</small>
                    </div>
                </div>
                
                <div class="small-1 columns">
                    <label>Count</label>
                    <input type="text" id="qty" placeholder="Count" name="qty" required pattern="[1-1000]" data-tooltip aria-haspopup="true" class="tip-top" title="For example: 10">
                    <small class="error">Invalid value! </small>
                </div>
                
                <div class="small-1 columns">
                    <label>Time</label>
                    <input type="text" id="time" placeholder="HH:MM" name="time" required pattern="[0-0][0-8]:[0-5][0-9]+" data-tooltip aria-haspopup="true" class="tip-top" title="For example 08:00">
                    <small class="error">Valid time required. </small>
                </div>
                
                <div class="small-2 columns">
                    <label>Comments</label>
                    <textarea placeholder="Comments" id="cmds" name="cmds" ></textarea>
                </div>
                
                <div class="small-2 columns">
                    <label>&nbsp;</label>
                    <input type="button" class="small radius button" value="Submit" onclick="add_task();" name="submit" id="submit_1">
                    <input type="button" class="small radius button" value="Reset">
                </div>
                
            </div>
            <?php } ?>
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
    // sample script for check all are working or not

       $(function() {
           $('#task_des').hide();
        var judge= $("#judge").val();
        if(judge==1)
            load_teams();
        else
            load_tasks();
        
                filter();
   });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
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
            url: "./load_tasks.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task").html(html);
            }
        });
    }
    
    function load_sub_tasks(task_id)
    {
         var have_dsi=$("#task option:selected").attr("itemref");
         var dsi_type=$("#task option:selected").attr("itemid");
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
        
        if(have_dsi==1 && dsi_type==1)
        {
            $('#task_des_space').hide();
            $('#task_des').show('slow');
            load_task_dec(task_id);
        }
        else if(have_dsi==0 || dsi_type!=1)
        {
            $('#task_des').hide();
            $('#task_des_space').show('slow');
            
        }
    }

   function load_task_dec(tsk)
    {
        $.ajax({
            type: "POST",
            url: "./load_task_desc.php",
            data: "task_id=" + tsk,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task_desc").html(html);
            }
        });
    }
    
    function filter()
    {
        var date_for_rec=$('#date').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $( "#user_tasks_list" ).load( "./load_user_entries.php",  { "date": date_for_rec},function( response, status, xhr ) {
            if ( status == "success" ) 
            {
                $('#loading_user_data').html('&nbsp;');
            }
        });
    }
    
    function check_remaining_hrs(date)
    {
        $('#remain_hrs').html('<center><img src="../img/loading.gif"></center>');
        $.ajax({
            type: "POST",
            url: "./remain_hrs.php",
            data:"date="+date,
            dataType: "json", 
            success: function(msg)
            {
                $('#remain_hrs').html(msg[0]+' hours remaining for the date:'+date);
                if(msg[1]<=0)
                {
                    $('#submit_1').prop("disabled",true);
                }
                else
                {
                    $('#submit_1').prop("disabled",false);
                }
            }
        });
    }
    
    function add_task()
    {
        var date=$('#date').val();
        var team=$('#team').val();
        var task=$('#task').val();
        var sub_task=$('#sub_task').val();
        var have_dsi=$("#task option:selected").attr("itemref");
        var dsi_type=$("#task option:selected").attr("itemid");
        var count=$('#qty').val();
        var time=$('#time').val();
        var cmds=$('#cmds').val();
        
        if(date=="" || team==""  ||  task=="" || sub_task=="" || time=="") // || count==""
        {
            $(".msgs").html("<font color=red>Pls fill all fields</font>");
            return false;
        }
        
        if(have_dsi==1 && dsi_type==1)
        {
            var task_desc=$('#task_desc').val();
            if(task_desc=="" || task_desc=='null')
            {
                $(".msgs").html("<font color=red>Pls fill all fields</font>");
                return false;
            }
            var data="date="+date+"&&team="+team+"&&task="+task+"&&sub_task="+sub_task+"&&task_desc="+task_desc+"&&time="+time+"&&count="+count+"&&cmds="+cmds+"&&have_dsi="+have_dsi+"&&dsi_type="+dsi_type;
        }
        else if(have_dsi==0 || dsi_type!=1)
        {
            var data="date="+date+"&&team="+team+"&&task="+task+"&&sub_task="+sub_task+"&&time="+time+"&&count="+count+"&&cmds="+cmds+"&&have_dsi="+have_dsi+"&&dsi_type="+dsi_type;
        }
        else
        {
            $(".msgs").html("<font color=red>Internal error occured! :(</font>");
            return false;
        }
        $('.msgs').html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./add_tasks_inner.php",
            data:data,
            dataType: "json", 
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    $(".msgs").html("<font color=red'>"+msg[1]+"</font>");
                }
                else
                {
                    $(".msgs").html("<font color='green'>"+msg[1]+"</font>");
                    filter();
                $('#form_user_tasks')[0].reset();
                }
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
    $( "#date" ).datepicker({
        changeMonth: true,
        changeYear:true,
        dateFormat:"yy-mm-dd" });
  });
</script>
<!-- Date picker files and function end-->