<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

    $query_get_tasks="select user_id,user_name FROM amz_login WHERE user_type='1' ORDER BY user_name ASC";
    $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
    echo '<option value="">-- Select All--</option>';
    if(mysqli_num_rows($result_get_tasks))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_tasks))
        {
            ?>
                <option value="<?php echo $result_tasks->user_id?>"><?php echo $result_tasks->user_name?></option>
            <?php
        }
    }
?>