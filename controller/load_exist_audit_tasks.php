<?php
session_start();
require_once '../classes/db.class.php';
require_once './data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$where_1 = $where_2 = $where_final = "";
$team=$_POST['team'];

if($team!="")
{
    $where_1=" team='$team' ";
}
else
{
    $available_teams=  implode(",",$_SESSION['team_id']);
    $where_1="team IN ($available_teams) ";
}

$where_final=$where_1;//.$where_2;
?>
<table width="100%">
    <tr id="data_header" align="center">
        <td>S.No.</td>
        <td>Team</td>
        <td>Audit task</td>
        <td>Added by</td>
        <td>Action</td>
    </tr>
    <?php
    $query = "SELECT * FROM  amz_audit_tasks WHERE ".$where_final." ORDER BY create_date DESC";
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    if (mysqli_num_rows($result)) 
    {
        while ($result_row = mysqli_fetch_object($result)) 
        {
            $class = $i % 2;
            echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
            ?>
            <td><?php echo $i;?></td>
            <td><?php echo $team_array[$result_row->team]?></td>
            <td><?php echo $result_row->task_name?></td>
            <td><?php echo $user_array[$result_row->created_by]?></td>
            <td><a onclick="delete_audit_task('<?php echo $result_row->s_no?>');">Delete</a></td>
            </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="5" align="center">No tasks available!</td>
    </tr>
    <?php
    }
    ?>
</table>