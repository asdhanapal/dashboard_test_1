<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['task_id']!="")
{
    $team_id=$_POST['task_id'];
    $query_get_tasks="select tdi_no,task_info FROM amz_task_desc WHERE status='1' AND deletion='0' AND task_id='$team_id' ORDER BY task_info ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="" disabled="">Select Task desc</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
<option value="<?php echo $result_tasks->tdi_no?>"><?php echo $result_tasks->task_info?></option>
            <?php
        }
    }
}
else
    echo '<option value="" disabled="">No tasks available</option>';
?>