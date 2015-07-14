<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['team_id']!="")
{
    $team_id=$_POST['team_id'];
    $query_get_tasks="select s_no,release_name FROM amz_releases WHERE release_status='1' AND team_id='$team_id' ORDER BY release_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select --</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->s_no?>"><?php echo $result_tasks->release_name?></option>
            <?php
        }
    }
    else
    {
        ?>
                <option value="" disabled=" ">No releases available!</option>
        <?php
    }
}
else {?>
    <option value="">-- Select Team first--</option>
<?php }
?>