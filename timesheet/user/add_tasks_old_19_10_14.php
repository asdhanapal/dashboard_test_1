<?php
include_once '../includes/header.php';
include_once '../includes/date_picker.php';
print_r($_SESSION);
?>
<input type="hidden" name="team" id="team" value="<?php echo $_SESSION['team_count'] ?>">
<br>
<div class="row">
    <div class="large-12 columns">
        <form id="form_user_tasks" data-abide>
        <div class="panel">
            <center><span class="msgs"></span></center>
            
            <?php if(1) { ?>
            <div class="row">
                <div class="small-3 columns">
                    <label>Select Date</label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select the date from the date picker">
                     <small class="error">Date is required</small>
                </div>
                <div class="small-3 columns">
                    
                </div>
                <div class="small-3 columns">
                    <label>Select Task</label>
                    <select id="task_0" name="task_0" onchange="update_sub_task(0,this.value);" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                <div class="small-3 columns"></div>
            </div>
            <?php } else { ?>
            <div class="row">
                <div class="small-4 columns"></div>
                <div class="small-4 columns"></div>
                <div class="small-4 columns"></div>
            </div>
            <?php } ?>
            
            <div class="row">
                <div class="small-3 columns"></div>
                <div class="small-3 columns"></div>
                <div class="small-3 columns"></div>
                <div class="small-3 columns"></div>
            </div>

            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            <div class="row">
                <div class="large-4 medium-4 columns">
                    <label>Select Date</label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select the date from the date picker">
                     <small class="error">Date is required</small>
                </div>
            </div>

            <div class="row">
                <input type="hidden" name="hidden_add_row" id="hidden_add_row" value="1">
                <!-- Task and spinner-->
                <div class="large-2 medium-2 columns">
                    <label>Select Task</label>
                    <select id="task_0" name="task_0" onchange="update_sub_task(0,this.value);" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                <div class="large-1 medium-1 columns">
                    <label>&nbsp;</label>
                    <span class="loading_tasks_0"></span>
                </div>
                
                
                <!-- Sub task and spinner-->
                <div class="large-2 medium-2 columns">
                    <label>Select sub task</label>
                    <select id="sub_task_0" name="sub_task_0" required>
                        <option value=""> -- Select task first --</option>
                    </select>
                    <small class="error">Sub task is required!</small>
                </div>
<div class="large-1 medium-1 columns">
                    <label>&nbsp;</label>
                    <span class="loading_sub_tasks_0"></span>
                </div>                
                
				<!--  Finished assests -->
                <div class="large-2 medium-2 columns">
                    <label>No of books did</label>
                    <input type="text" id="qty_0" placeholder="Count" name="qty_0" required pattern="[1-1000]" data-tooltip aria-haspopup="true" class="tip-top" title="For example: 10">
                    <small class="error">Invalid value! </small>
                </div>
                
                
                <!-- Time field-->
                <div class="large-2 medium-2 columns">
                    <label>Time</label>
                    <input type="text" id="time_0" placeholder="HH:MM" name="time_0" required pattern="[0-0][0-8]:[0-5][0-9]+" data-tooltip aria-haspopup="true" class="tip-top" title="For example 08:00">
                    <small class="error">Valid time required. </small>
                </div>
                
                <!-- Comments field-->
                <div class="large-2 medium-2 columns">
                    <label>Comments</label>
                    <textarea placeholder="Comments" id="cmds_0" name="cmds_0" ></textarea>
                </div>
                

            </div>
            
            <div id="more_tasks">
                    
            </div>
 
            <div class="row">
                <div class="large-12 columns" align="right">
                    <input type="button" class="small radius button" value="Submit" onclick="add_task();" name="submit">&nbsp;<input type="button" class="small radius button" value="Reset">
                </div>
            </div>
        </div>
           </form>
        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel">
                    <span id="loading_user_data"></span>
                    <div id="user_tasks_list">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // sample script for check all are working or not

    $(document).ready(function(){
        $("button").click(function(){
            $("p").toggle(1000);
        });
    });

    $(function(){
        load_tasks(0);
        filter();
    });

    function load_tasks(team_row)
    {
        var id=$("#team").val();
        $('.loading_tasks').html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./load_tasks.php",
            data:"team_id="+id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task_"+team_row).html(html);
                $('.loading_tasks_'+team_row).html('');
            }
        });
    }
    
    function update_sub_task(task_row,task_id)
    {
        $('.loading_sub_tasks_'+task_row).html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./load_sub_tasks.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#sub_task_"+task_row).html(html);
                $('.loading_sub_tasks_'+task_row).html('');
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
                $('#loading_user_data').html('');
            }
        });
    }
    
    function check_remaining_hrs(date)
    {
        //alert("");
    }
    
    function add_task()
    {
//        var arr = [];
//        var a=$("#form_user_tasks").serializeArray();
        arr=($("#form_user_tasks").serialize());
//        alert(arr);
       // console.log(arr);
//        var date=$('#date').val();
//        var task=$('#task').val();
//        var sub_task=$('#sub_task').val();
//        var time=$('#time').val();
//        var cmds=$('#cmds').val();
//        if(date=="" || task=="" || sub_task=="" || time=="")
//        {
//            $(".msgs").html("<font color=red>Pls fill all fields</font>");
//            return false;
//        }
//        //alert(date+task+time+cmds);
        $('.msgs').html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./add_tasks_inner.php",
            data:arr,
            dataType: "json", 
            success: function(msg)
            {
             //   var html = $.trim(msg);
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
    
    function add_rows()
    {
        var count=$('#hidden_add_row').val();
        var del_row=count-1;
        $('#del_close_img_'+del_row).html('');
        var new_task ="<div class=\"row\" style=\"display:none;\" id=\"del_row_"+count+"\"><div class=\"large-2 medium-2 columns\"><label>Select Task</label><select id=\"task_"+count+"\" name=\"task_"+count+"\" onchange=\"update_sub_task("+count+",this.value);\"><option> Loading...</option></select></div>";
        new_task+="<div class=\"large-1 medium-1 columns\"><label>&nbsp;</label><span class=\"loading_tasks_"+count+"\"></span></div><div class=\"large-2 medium-2 columns\"><label>Select sub task</label><select id=\"sub_task_"+count+"\" name=\"sub_task_"+count+"\"><option value=\"\"> -- Select task first --</option></select></div>";
        new_task+="<div class=\"large-1 medium-1 columns\"><label>&nbsp;</label><span class=\"loading_sub_tasks_"+count+"\"></span></div><div class=\"large-2 medium-2 columns\"><label>Time</label><input type=\"text\" id=\"time_"+count+"\" name=\"time_"+count+"\" placeholder=\"HH:MM\"  required pattern=\"[0-0][0-8]:[0-5][0-9]+\" data-tooltip aria-haspopup=\"true\" class=\"tip-top\" title=\"For example 08:00\"><small class=\"error\">Valid time required. </small></div>";
        new_task+="<div class=\"large-3 medium-3 columns\"><label>Comments</label><textarea placeholder=\"Comments\" id=\"cmds_"+count+"\" name=\"cmds_"+count+"\"></textarea></div><div class=\"large-1 medium-1 columns\" id=\"del_close_img_"+count+"\"><label>&nbsp;</label><br><a href='#' onclick='del_rows("+count+");'><img src='../img/error.ico' width='30%' height='30%'></a></div></div>";
        //$(new_task).appendTo("#more_tasks",50000).fadeIn('slow');
        $(new_task).appendTo("#more_tasks").show('slow');
        load_tasks(count);    
        count++;
        $('#hidden_add_row').val(count);
        return false;
    }
    
    function del_rows(del_row_id)
    {
//        $('#del_row_'+del_row_id).hide('slow').remove();
//        alert(del_row_id);
        $('#del_row_'+del_row_id).hide('slow',function(){  //slideUp or hide or fadeOut
            $(this).remove();
        });
//        del_row_id=del_row_id-1;
        $('#hidden_add_row').val(del_row_id);
        var add_row_id=del_row_id-1;
        var close_img="<label>&nbsp;</label><br><a href='#' onclick='del_rows("+add_row_id+");'><img src='../img/error.ico' width='30%' height='30%'></a>";
//        alert(close_img);
        $('#del_close_img_'+add_row_id).hide().html(close_img).fadeIn('slow')
        return false;
    }
  //$('#error_msg').delay(5000).fadeOut("slow");  
</script>

<!--<button>Test </button>
<p>Test content for check all jquery lib are included or not?</p>
<p>Test content for all css and foundation included or not?</p>-->

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