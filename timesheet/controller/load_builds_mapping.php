<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['team_id']!="")
{
    $team_id=$_POST['team_id'];
    echo $query_get_tasks="select build_no,build_name FROM  amz_builds WHERE team_id='$team_id' AND build_no NOT IN (SELECT build_no FROM amz_build_mapping) ORDER BY build_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select --</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->build_no?>"><?php echo $result_tasks->build_name?></option>
            <?php
        }
    }
    else
    {
        ?>
                <option value="" disabled=" ">No builds available!</option>
        <?php
    }
}
else {?>
    <option value="">-- Select Team first--</option>
<?php }
?>