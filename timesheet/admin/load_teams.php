<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

    $query_get_tasks="select team_id,team_name FROM amz_teams ORDER BY team_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select All--</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->team_id?>"><?php echo $result_tasks->team_name?></option>
            <?php
        }
    }
    else {?>
            <option value="">-- No teams available--</option>
<?php }

?>