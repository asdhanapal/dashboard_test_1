<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$team=$_POST['team'];
$query_get_tasks="select s_no,task_name FROM amz_audit_tasks WHERE  team='$team' ORDER BY task_name ASC";
$result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
echo '<option value="">-- Select --</option>';
if(mysqli_num_rows($result_get_tasks))
{
    while($result_tasks=  mysqli_fetch_object($result_get_tasks))
    {
        ?>
            <option value="<?php echo $result_tasks->s_no?>"><?php echo $result_tasks->task_name?></option>
        <?php
    }
}
else
    echo "<option value=\"\" disabled=\"\"> --No tasks available --</option>";
?>