<?php
session_start();
require_once '../classes/db.class.php';
require_once './data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$where_1 = $where_2 = $where_3 = $where_4 = $where_5 = $where_final = "";

$date=mysqli_real_escape_string($dbcon,$_POST['date']);
$team=$_POST['team'];
$user=$_POST['user'];
$task=$_POST['task'];
$impact=$_POST['impact'];

if($date!="")
{
    $where_1=" date='$date' ";
}
else
{
    $where_1= " date!=''" ;
}

if($team!="")
{
    $where_2=" AND team='$team' ";
}
else
{
    $available_teams=  implode(",",$_SESSION['team_id']);
    $where_2=" AND team IN ($available_teams) ";
}

if($user!="")
{
    $where_3=" AND user='$user' ";
}
else
{
    $where_3="";
}

if($task!="")
{
    $where_4=" AND task='$task' ";
}
else
{
    $where_4="";
}

if($impact!="")
{
    $where_5=" AND impact='$impact' ";
}
else
{
    $where_5="";
}


$where_final=$where_1.$where_2.$where_3.$where_4.$where_5;
?>
<table width="100%">
    <tr id="data_header" align="center">
        <td>S.No.</td>
        <td>Date</td>
        <td>Team</td>
        <td>User</td>
        <td>task</td>
        <td>Audit</td>
        <td>Misses</td>
        <td>Impact</td>
        <td>Added by</td>
        <td>Action</td>
    </tr>
    <?php
    $query = "SELECT * FROM amz_audits WHERE ".$where_final." ORDER BY create_date DESC";
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
            <td><?php echo $result_row->date;?></td>
            <td><?php echo $team_array[$result_row->team]?></td>
            <td><?php echo $user_array[$result_row->user]?></td>
            <td><?php echo $audit_task_array[$result_row->task]?></td>
            <td><?php echo $result_row->audit;?></td>
            <td><?php echo $result_row->misses;?></td>
            <td><?php echo $impact_array[$result_row->impact];?></td>
            <td><?php echo $user_array[$result_row->created_by]?></td>
            <td><a onclick="delete_audit('<?php echo $result_row->s_no?>');">Delete</a</td>
        </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="10" align="center">No entries available!</td>
    </tr>
    <?php
    }
    ?>
</table>