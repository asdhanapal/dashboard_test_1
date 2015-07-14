<?php
session_start();
require_once '../classes/db.class.php';
require_once './data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$where_1 = $where_2 = $where_3 = $where_4 = $where_final = "";

$team=$_POST['team'];
$release=$_POST['release'];
$mode=$_POST['mode'];
$date=mysqli_real_escape_string($dbcon,$_POST['date']);

if($team!="")
{
    $where_1=" team='$team' ";
}
else
{
    $available_teams=  implode(",",$_SESSION['team_id']);
    $where_1="team IN ($available_teams) ";
}
if($date!="")
{
    $where_2=" AND date='$date' ";
}
if($release!="")
{
    $where_3=" AND release_no='$release' ";
}
if($mode!="")
{
    $where_4=" AND sub_task='$mode' ";
}

$where_final=$where_1.$where_2.$where_3.$where_4;
?>
<table width="100%">
    <tr id="data_header" align="center">
        <td>S.No.</td>
        <td>Date</td>
        <td>Team</td>
        <td>Release</td>
        <td>Task</td>
        <td>Mode</td>
        <td>Count</td>
        <td>Added by</td>
        <td>Action</td>
    </tr>
    <?php
    $query = "SELECT * FROM  amz_tc_addition WHERE ".$where_final." ORDER BY create_date DESC";
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
            <td><?php echo $release_array[$result_row->release_no]?></td>
            <td><?php echo $test_addition_task[$result_row->task]?></td>
            <td><?php echo $test_addition_sub_task[$result_row->sub_task]?></td>
            <td><?php echo $result_row->count?></td>
            <td><?php echo $user_array[$result_row->added_by]?></td>
            <td><a onclick="delete_entry('<?php echo $result_row->s_no?>');">Delete</a</td>
        </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="9" align="center">No entries available!</td>
    </tr>
    <?php
    }
    ?>
</table>