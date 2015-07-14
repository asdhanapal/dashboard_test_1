<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if($_POST['team']!="")
{
    $build_no=$_POST['team'];
    $query_get_builds="select build_no,build_name FROM amz_builds WHERE build_status='1' AND team_id='$build_no' ORDER BY build_name ASC";
    $result_get_builds= $conn->runsql($query_get_builds,$dbcon);
    echo '<option value="">-- Select build --</option>';
    if(mysqli_num_rows($result_get_builds))
    {
        while($result_tasks=  mysqli_fetch_object($result_get_builds))
        {
            ?>
                <option value="<?php echo $result_tasks->build_no?>"><?php echo $result_tasks->build_name?></option>
            <?php
        }
    }
}
 else { ?>
    <option value="">Team is null!</option>
<?php } ?>