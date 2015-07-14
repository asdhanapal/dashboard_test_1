<?php
//include_once './data_page.php';
//print_r($_GET);
//print_r($_SESSION);
die("<center>Under maintenance!");
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
$edited_by=$_SESSION['sda_id'];

$subject=$_GET['subject'];
$edit_item=$_GET['val'];

switch ($subject)
{
    case "team":
        //echo "team edit".$edit_item;
        $query_team_edit_select="SELECT team_id,team_name FROM amz_teams WHERE team_id='$edit_item'";
        $result_1= $conn->runsql($query_team_edit_select,$dbcon);
        $result_row_1=  mysqli_fetch_object($result_1);
        ?>        
          <form >
            <div class="row">
                
                <div class="small-12 columns"><center><span id="final_msg"></span></center></div>
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-3 columns"><label class="left inline">Team name: </label></div>
                        <div class="row collapse">
                            <div class="small-7 columns">
                                <input type="text" placeholder="Team name" value="<?php echo $result_row_1->team_name?>" id="team_name">
                            </div>
                            <div class="small-2 columns">
                                <a href="#" class="button postfix" onclick="return modify_team('<?php echo $result_row_1->team_id?>');">Submit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        break;
    
    case "task":
        $query_team_edit_select="SELECT * FROM amz_tasks WHERE task_id='$edit_item'";
        $result_1= $conn->runsql($query_team_edit_select,$dbcon);
        $result_row_1=  mysqli_fetch_object($result_1);
        ?>        
          <form>
            <div class="row">
                <div class="small-12 columns"><center><span id="final_msg">please hover mouse over the field to get more details.<br>.</span></center></div>
                    <div class="row">
                        <div class="small-3 columns"><label class="left inline">Task name: </label></div>
                            <div class="small-7 columns">
                                <input type="text" placeholder="Task name" value="<?php echo $result_row_1->task_name?>" id="task_name">
                            </div>
                    </div>
                
                    <div class="row">
                        <div class="small-3 columns"><label for="have_cf">Have CF? </label></div>
                            <div class="small-7 columns">
                                <input id="have_cf" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task have conversion factor." <?php if($result_row_1->cf_avail) echo "checked=''";?> onchange="show_hide();">
                            </div>
                    </div>
                    
                <div class="row" id="autocf">
                    <div class="small-3 columns"><label for="auto_cf">Auto CF? </label></div>
                            <div class="small-7 columns">
                                    <input id="auto_cf" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task is applicable for 'auto conversion factor'." <?php if($result_row_1->auto_cf) echo "checked=''";?>>
                            </div>
                    </div>
                
                <div class="row">
                    <div class="small-3 columns"><label for="have_st">Have Sub tasks? </label></div>
                            <div class="small-7 columns">
                                    <input id="have_st" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task have sub tasks." <?php if($result_row_1->have_st) echo "checked=''";?>>
                            </div>
                    </div>
                
                <div class="row">
                    <div class="small-3 columns"><label for="have_td">Have task desc.? </label></div>
                            <div class="small-7 columns">
                                
                                    <input id="have_td" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task have task description." <?php if($result_row_1->have_tdi) echo "checked=''";?>>
                                
                            </div>
                    </div>
                
                <div class="row">
                    <div class="small-3 columns"><label for="op">Operational task? </label></div>
                            <div class="small-7 columns">
                                    <input id="op" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task is operational task." <?php if($result_row_1->op_type) echo "checked=''";?>>
                            </div>
                    </div>
                
                <div class="row">
                            <div class="small-3 small-centered columns ">
                                <input type="button" class="button tiny" onclick="return modify_task('<?php echo $result_row_1->task_id?>');"  value="Submit">
                                </div>
                            </div>
                    </div>
                </div>
        </form>
<?php        
        break;

    case "subtask":
        $query_team_edit_select="SELECT * FROM amz_sub_tasks WHERE sub_task_id='$edit_item'";
        $result_1= $conn->runsql($query_team_edit_select,$dbcon);
        $result_row_1=  mysqli_fetch_object($result_1);
        ?>        
          <form>
            <div class="row">
                <div class="small-12 columns"><center><span id="final_msg">please hover mouse over the field to get more details.<br>.</span></center></div>
                    <div class="row">
                        <div class="small-3 columns"><label class="left inline">Sub Task name: </label></div>
                            <div class="small-7 columns">
                                <input type="text" placeholder="Sub Task name" value="<?php echo $result_row_1->sub_task_name?>" id="stask_name">
                            </div>
                    </div>
                    
                <div class="row">
                    <div class="small-3 columns"><label for="auto_cf">Auto CF? </label></div>
                            <div class="small-7 columns">
                                    <input id="auto_cf" type="checkbox" data-tooltip aria-haspopup="true" class="tip-right" title="Check this if the task is applicable for 'auto conversion factor'." <?php if($result_row_1->auto_cf) echo "checked=''";?>>
                            </div>
                    </div>
                    <?php if($result_row_1->cf_change) {?>            
                    <div class="row">
                        <div class="small-3 columns"><label for="cf_type">Task type: </label></div>
                            <div class="small-7 columns">
                                <select id='cf_type' data-tooltip aria-haspopup="true" class="tip-right" title="Select the task priority!">
                                    <option value="1" <?php if($result_row_1->cf_change==1) echo "selected";?>>LC</option>
                                    <option value="2" <?php if($result_row_1->cf_change==2) echo "selected";?>>MC</option>
                                    <option value="3" <?php if($result_row_1->cf_change==3) echo "selected";?>>HC</option>
                                </select>
                            </div>
                        
                    </div>
                    <?php }
                    else {
                        ?>
                <input type="hidden" value="0" id="cf_type">
                            <?php
                    }
                    ?>
                
                <div class="row">
                            <div class="small-3 small-centered columns ">
                                <input type="button" class="button tiny" onclick="return modify_sub_task('<?php echo $result_row_1->sub_task_id?>');"  value="Submit">
                                </div>
                            </div>
                    </div>
                </div>
        </form>
<?php        
        break;
    
    case "td":
        $query_team_edit_select="SELECT tdi_no,task_info FROM amz_task_desc WHERE tdi_no='$edit_item'";
        $result_1= $conn->runsql($query_team_edit_select,$dbcon);
        $result_row_1=  mysqli_fetch_object($result_1);
        ?>        
          <form >
            <div class="row">
                
                <div class="small-12 columns"><center><span id="final_msg"></span></center></div>
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-3 columns"><label class="left inline">Task desc. name: </label></div>
                        <div class="row collapse">
                            <div class="small-7 columns">
                                <input type="text" placeholder="Task description name" value="<?php echo $result_row_1->task_info?>" id="tdi">
                            </div>
                            <div class="small-2 columns">
                                <a href="#" class="button postfix" onclick="return modify_tdi('<?php echo $result_row_1->tdi_no?>');">Submit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        break;
    
    default:
        echo "Internal server error!";
}
?>


<script>
show_hide();

function modify_tdi(id)
{
    $('#final_msg').html('<img src="../img/loading.gif">');
    var value=$('#tdi').val();
    if(value=="")
    {
        $("#final_msg").hide().html("<font color=red'>Task desc. cant be null!</font>").fadeIn('slow');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "edit_subjects.php?action=td",
        dataType: "json", 
        data: "id="+id+"&value="+value,
        success: function(msg)
        {
                if(msg[0]=='F')
                {
                    $("#final_msg").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    $("#final_msg").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
        }
    });
    return false;
}

function modify_sub_task(id)
{
    $('#final_msg').html('<img src="../img/loading.gif">');
    var value=$('#stask_name').val();
    var cf_type=$('#cf_type').val();
    if($('#auto_cf').is(":checked"))
        var auto_cf=1;
    else
        var auto_cf=0;
    
    if(value=="")
    {
        $("#final_msg").hide().html("<font color=red'>Sub task name cant be null!</font>").fadeIn('slow');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "edit_subjects.php?action=subtask",
        dataType: "json", 
        data: "id="+id+"&value="+value+"&auto_cf="+auto_cf+"&cf_type="+cf_type,
        success: function(msg)
        {
                if(msg[0]=='F')
                {
                    $("#final_msg").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    $("#final_msg").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
        }
    });
    return false;
}    


function show_hide()
{
    if($('#have_cf').is(":checked"))
           $('#autocf').show('slow');
        else
           $('#autocf').hide('slow'); 
    
}
    
function modify_task(id)
{
    $('#final_msg').html('<img src="../img/loading.gif">');
    var value=$('#task_name').val();
    if($('#have_cf').is(":checked"))
    {
        var have_cf=1;
        if($('#auto_cf').is(":checked"))
           var auto_cf=1;
        else
            var auto_cf=0;
    }
    else
    {
        var have_cf=0;
        var auto_cf=0;
    }
 
    if($('#have_st').is(":checked"))
            var have_st=1;
        else
            var have_st=0;

    if($('#have_td').is(":checked"))
            var have_td=1;
        else
            var have_td=0;
    if($('#op').is(":checked"))
            var op=1;
        else
            var op=0;

    if(value=="")
    {
        $("#final_msg").hide().html("<font color=red'> Task name cant be null!</font>").fadeIn('slow');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "edit_subjects.php?action=task",
        dataType: "json", 
        data: "id="+id+"&value="+value+"&have_cf="+have_cf+"&auto_cf="+auto_cf+"&have_st="+have_st+"&have_td="+have_td+"&op="+op,
        success: function(msg)
        {
                if(msg[0]=='F')
                {
                    $("#final_msg").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    $("#final_msg").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
        }
    });
    return false;
}    
    
function modify_team(id)
{
    $('#final_msg').html('<img src="../img/loading.gif">');
    var value=$('#team_name').val();
    if(value=="")
    {
        $("#final_msg").hide().html("<font color=red'>Team name cant be null!</font>").fadeIn('slow');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "edit_subjects.php?action=team",
        dataType: "json", 
        data: "id="+id+"&value="+value,
        success: function(msg)
        {
                if(msg[0]=='F')
                {
                    $("#final_msg").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    $("#final_msg").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
        }
    });
    return false;
}
</script>

<script>
$(document).foundation();
</script>