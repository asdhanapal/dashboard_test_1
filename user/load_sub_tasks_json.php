<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['task_id']!="")
{
    $task_id=$_POST['task_id'];
    $query_get_tasks="select sub_task_id,sub_task_name FROM amz_sub_tasks WHERE task_status='1' AND deletion='0' AND task_id='$task_id' ORDER BY sub_task_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select Sub Task--</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->sub_task_id?>"><?php echo $result_tasks->sub_task_name?></option>
            <?php
        }
    }
}
 else {?>
                <option value="" disabled="" >No sub task available</option>
<?php }

?>