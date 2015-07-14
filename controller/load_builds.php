<?php
session_start();
require_once '../classes/db.class.php';
require_once './data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$where_1 = $where_2 = $where_final = "";

$team=$_POST['team'];
$build=mysqli_real_escape_string($dbcon,$_POST['build']);

if($team!="")
{
    $where_1=" team_id='$team' ";
}
else
{
    $available_teams=  implode(",",$_SESSION['team_id']);
    $where_1="team_id IN ($available_teams) ";
}

if($build!="")
{
    $where_2=" AND build_name like '%$build%' ";
}

$where_final=$where_1;//.$where_2;
?>
<table width="100%">
    <tr id="data_header" align="center">
        <td>S.No.</td>
        <td>Team</td>
        <td>Build name</td>
        <td>Added by</td>
        <td>Build status</td>
    </tr>
    <tr style="background-color: #DDDDDD;">
        <td colspan="5" align="center"><b>Active builds</b></td>
    </tr>
    <?php
    $query = "SELECT * FROM amz_builds WHERE ".$where_final." AND build_status=1 ORDER BY create_date DESC";
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
            <td><?php echo $team_array[$result_row->team_id]?></td>
            <td><?php echo $result_row->build_name?></td>
            <td><?php echo $user_array[$result_row->added_by]?></td>
            <td>
                    <?php if($result_row->build_status==1) { ?>
                        Activated&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->build_no?>',0);">Disable?</a>
                    <?php } else {?>
                        Disabled&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->build_no?>',1);">Activate?</a>
                    <?php }?>
            </td>
        </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="5" align="center">No active builds available!</td>
    </tr>
    <?php
    }
    ?>
    <tr style="background-color: #DDDDDD;">
        <td colspan="5" align="center"><b>Inactive builds</b></td>
    </tr>
    <?php
    $query = "SELECT * FROM amz_builds WHERE ".$where_final." AND build_status=0 ORDER BY create_date DESC";
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
            <td><?php echo $team_array[$result_row->team_id]?></td>
            <td><?php echo $result_row->build_name?></td>
            <td><?php echo $user_array[$result_row->added_by]?></td>
            <td>
                    <?php if($result_row->build_status==1) { ?>
                        Activated&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->build_no?>',0);">Disable?</a>
                    <?php } else {?>
                        Disabled&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->build_no?>',1);">Activate?</a>
                    <?php }?>
            </td>
        </tr>
        <?php
        $i++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="5" align="center">No inactive builds available!</td>
    </tr>
    <?php
    }
    ?>
</table>