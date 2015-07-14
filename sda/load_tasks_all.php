<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$temp_team_id=$_POST['team_id'];
$temp_team_array=  explode(",",$temp_team_id);
//print_r($temp_team_array);
if(!empty($temp_team_array))
{
    $size=  sizeof($temp_team_array);
    for($i=0;$i<$size;$i++)
    {
        $team_id=$temp_team_array[$i];
        $query_get_tasks="select task_id,task_name FROM amz_tasks WHERE status='1' AND deletion='0' AND team_id='$team_id' ORDER BY task_name ASC";
        $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
        if(mysqli_num_rows($result_get_tasks))
        {
            while($result_tasks=  mysqli_fetch_object($result_get_tasks))
            {
                ?>
                    <option value="<?php echo $result_tasks->task_id?>"><?php echo $result_tasks->task_name?></option>
                <?php
            }
        }
    }
}
 else {
    echo "<option values='' disabled=''>-- Pls select team to view tasks --</option>";
}
?>