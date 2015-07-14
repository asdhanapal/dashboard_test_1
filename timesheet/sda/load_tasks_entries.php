<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['team_id']!="")
{
    $team_id=$_POST['team_id'];
    $query_get_tasks="select task_id,task_name,have_tdi,tdi_type,have_st,device_count FROM amz_tasks WHERE status='1' AND deletion='0' AND team_id='$team_id' ORDER BY task_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value=""> -- Select Task --</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        { ?>
                <option value="<?php echo $result_tasks->task_id?>"
                        itemid="<?php echo $result_tasks->tdi_type?>"
                        itemref="<?php echo $result_tasks->have_tdi?>"
                        itemprop="<?php echo $result_tasks->device_count?>"
                        itemtype="<?php echo $result_tasks->have_st?>"><?php echo $result_tasks->task_name?>
                </option>
         <?php }
    }
}
else
    echo '<option value="" disabled="">No teams  available</option>';
?>