<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
//print_r($_SESSION);
//$avail_teams=$_SESSION['team_id'];
//$size=  sizeof($avail_teams);
echo "<ol>";
//for($i=0;$i<$size;$i++)
//{
    $query_1="SELECT team_id,team_name FROM amz_teams where team_deletion='0'  ORDER BY team_name ASC"; //team_id='$avail_teams[$i]'
    $result_1= $conn->runsql($query_1,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        while($result_row_1=  mysqli_fetch_object($result_1))
        {
            $sec_team_id=$result_row_1->team_id;
            echo "<li class='team_style'>".$sec_team_name=$result_row_1->team_name?>
            &nbsp;&nbsp;&nbsp;
            <a href="#" data-reveal-id="edit_team" data-reveal-ajax="./edit_data.php?subject=team&&val=<?php echo $sec_team_id ?>">Edit</a>&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="del_ua('team','<?php echo $sec_team_id?>');">Delete</a>
            </li>
           
            <?php $query_2="SELECT task_id,task_name,op_type FROM amz_tasks where team_id='$sec_team_id' AND deletion=0 ORDER BY task_name ASC";
            $result_2= $conn->runsql($query_2,$dbcon);
            
            if(mysqli_num_rows($result_2))
            {
                echo "<ol>";
                while($result_row_2=  mysqli_fetch_object($result_2))
                {
                    $sec_task_id=$result_row_2->task_id;
                    $op_type=$result_row_2->op_type==0?"Non operation":"Operation";
                    ?><li id='hide_show_st' data-tooltip aria-haspopup="true" class="tip-right" title="Click the task name to view the sub tasks!" onclick='show_hide(<?php echo $sec_task_id?>);'>[<?php echo $op_type; ?>]&nbsp;&nbsp;<?php echo $sec_task_name=$result_row_2->task_name; ?>
                        &nbsp;&nbsp;&nbsp;
            <a href="#" data-reveal-id="edit_task" data-reveal-ajax="./edit_data.php?subject=task&&val=<?php echo $sec_task_id ?>">Edit</a>&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="del_ua('task','<?php echo $sec_task_id?>');">Delete</a> 
            </li>
           
                    </li>
                    <?php $query_3="SELECT sub_task_id,sub_task_name,cf_change FROM amz_sub_tasks where task_id='$sec_task_id' AND deletion=0 ORDER BY sub_task_name ASC";
                    $result_3= $conn->runsql($query_3,$dbcon);
                    
                    if(mysqli_num_rows($result_3))
                    {
                        echo "<ol class='hide_show_sts$sec_task_id' id='hide_show_sts'>";
                        while($result_row_3=  mysqli_fetch_object($result_3))
                        {
                            $sec_stask_id=$result_row_3->sub_task_id;
                            $task_cat=$result_row_3->cf_change;
                            if($task_cat==1)
                                $task_cat="[LC]";
                            elseif($task_cat==2)
                                $task_cat="[MC]";
                            elseif($task_cat==3)
                                $task_cat="[HC]";
                            else
                                $task_cat=""; ?>
                    <li class="option-content"><?php echo $sec_stask_name=$result_row_3->sub_task_name?>&nbsp;<?php echo $task_cat?>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#" data-reveal-id="edit_sub_task" data-reveal-ajax="./edit_data.php?subject=subtask&&val=<?php echo $sec_stask_id ?>">Edit</a>&nbsp;&nbsp;&nbsp;
                            <a href="#" onclick="del_ua('subtask','<?php echo $sec_stask_id?>');">Delete</a>
                            </li>
                                
                            </li>
                            <?php $query_5="SELECT tdi_no,task_info FROM amz_task_desc where task_id='$sec_task_id'  AND deletion=0 ORDER BY task_info ASC";
                            $result_5= $conn->runsql($query_5,$dbcon);
                            
                            if(mysqli_num_rows($result_5))
                            {
                                echo "<ol>";
                                while($result_row_5=  mysqli_fetch_object($result_5))
                                {
                                    $task_des_id=$result_row_5->tdi_no;
                                    ?>
                            <li class="td_style"><?php echo $result_row_5->task_info?>
                              &nbsp;&nbsp;&nbsp;
                            <a href="#" data-reveal-id="edit_task_des" data-reveal-ajax="./edit_data.php?subject=td&&val=<?php echo $task_des_id ?>">Edit</a>&nbsp;&nbsp;&nbsp;
                            <a href="#" onclick="del_ua('taskdes','<?php echo $task_des_id?>');">Delete</a>
                            </li>
                                
                                    </li>
                               <?php }
                                echo "</ol>";
                            }
                        }
                        echo "</ol>";
                    }
                    else
                    {
                               ?> 
                                    <ol class='hide_show_sts<?php echo $sec_task_id?>' id='hide_show_sts' style="list-style: none;">
                                        <li class="option-content">No sub tasks available under this task!</li>
                                    </ol>
                    <?php }
                }
                echo "</ol>";
            }
        }
    }
//}
echo "</ol>";

?>
<script>
$(document).foundation();
</script>


<div id="edit_team" class="reveal-modal small" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="edit_task" class="reveal-modal small" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="edit_sub_task" class="reveal-modal small" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="edit_task_des" class="reveal-modal small" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>
<script>
$('ol[class^="hide_show_sts"]').hide();
function show_hide(id)
{
  $('.hide_show_sts'+id).slideToggle('slow');
  
}
</script>

<style>
    #hide_show_st{background:#ddd;padding-left:25px;margin-top:2px;cursor:pointer;width:39%;}
  .option-content{background:#eee;padding-left:25px;margin-top:2px;cursor:pointer;width:38%;}
  .team_style {padding-left:25px;margin-top:2px;cursor:pointer;background-color: #d7cfcf; width: 40%}
  .td_style {padding-left:25px;margin-top:2px;cursor:pointer;background-color: #ffeeee;width:37%;}
</style>

<script>
function del_ua(subject,id)
    {
        if(confirm('Are you sure to delete?'))
        {
            
            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: "del_subjects.php?action="+subject,
                dataType: "json", 
                data: "id="+id,
                success: function(msg)
                {
                     if(msg[0]=='F')
                    {
                        $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                    }
                    else
                    {
                        $(".msgs").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                        
                    }
                }
            });
            filter();
            return false;
        }
     }
</script>

<script>
//$(document).on('close.fndtn.reveal', '#edit_team_4[data-reveal]', function () {
//  alert("Good bye1");
//});

//$(document).on('close.fndtn.reveal', '#edit_team_2[data-reveal]', function () {
//  alert("Good bye2");
//});
//$("#").reveal({ "closed": function () { alert("Good bye") } });
</script>

<script>
    //$('ol[class^="hide_show_sts"]').hide();
        $(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
          filter();
        });
        
        //$(document).on('close.fndtn.reveal', '#edit_team_4[data-reveal]', function () {
//  alert("Good bye1");
//});

</script>