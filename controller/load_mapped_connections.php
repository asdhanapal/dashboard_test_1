<?php
session_start();
require_once '../classes/db.class.php';
require_once './data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$where_1 = $where_2 = $where_final = "";

$team=$_POST['team'];
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
else
{
    $where_2="";
}


$where_final=$where_1.$where_2;
?>
<table width="100%">
    <tr id="data_header" align="center">
        <td>S.No.</td>
        <td>Date</td>
        <td>Team</td>
        <td>Release</td>
        <td>Build</td>
        <td>Run status</td>
        <td>Build info</td>
        <td>Added by</td>
        <td>Action</td>
    </tr>
    <?php
    $query = "SELECT * FROM  amz_build_mapping WHERE ".$where_final." ORDER BY create_date DESC";
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
            <td><?php echo $build_array[$result_row->build_no]?></td>
            <td><?php echo $result_row->run_status==0?"Partial":"Complete"?></td>
            <td><?php echo $result_row->build_info==0?"Official":"Non-off"?></td>
            <td><?php echo $user_array[$result_row->created_by]?></td>
            <td><a onclick="delete_connection('<?php echo $result_row->s_no?>');">Delete</a</td>
        </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="9" align="center">No connections available!</td>
    </tr>
    <?php
    }
    ?>
</table>