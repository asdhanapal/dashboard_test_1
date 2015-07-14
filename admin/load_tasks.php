<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['team_id']!="")
{
    $team_id=$_POST['team_id'];
    $query_get_tasks="select task_id,task_name FROM amz_tasks WHERE status='1' AND deletion='0' AND team_id='$team_id' ORDER BY task_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select all --</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->task_id?>"><?php echo $result_tasks->task_name?></option>
            <?php
        }
    }
    else {?>
                <option value="" disabled="">-- No tasks available --</option>
<?php }

}
 else {
    echo "<option values='' disabled=''>-- Pls select team to view tasks --</option>";
}



?>