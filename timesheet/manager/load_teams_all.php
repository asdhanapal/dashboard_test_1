<?php
session_start();
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(!empty($_SESSION['team_id']))
{
    $team_id=$_SESSION['team_id'];
    $team_text=implode(",", $team_id);
        echo $query_get_teamss="select team_id,team_name FROM amz_teams WHERE status='1' AND team_deletion='0' AND team_id IN ($team_text) ORDER BY team_name ASC";
        $result_get_teams= $conn->runsql($query_get_teamss,$dbcon);
        
        while($result_teams=  mysqli_fetch_object($result_get_teams)) {
        ?>
        <option value="<?php echo $result_teams->team_id?>"><?php echo $result_teams->team_name?></option>
        <?php
    }

}
else
    echo '<option value="">-- No teams  available--</option>';
?>