<?php
session_start();
include_once './data_page.php';
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
$user_type=array(1=>"DA",2=>"SDA",3=>"Manager",4=>"Admin");


//print_r($_POST);
$where_1 = $where_2 = $where_3 = $where_4 = $where_5=$where_final="";//=$where_6=$where_7=

$team=$_POST['team'];
$userid=mysqli_real_escape_string($dbcon,$_POST['user']);
$f_name=mysqli_real_escape_string($dbcon,$_POST['f_name']);
$status=$_POST['status'];
$new_users=$_POST['new_users'];

//if(!empty($team))
//{
//    for ($index = 0; $index < count($team); $index++) {
//        $where_1.=" AND team_id='$team[$index]' ";
//    }
//}
//echo $where_1;
if($userid!="")
{
    $where_2=" AND user_name like '%$userid%' ";
}

if($f_name!="")
{
    $where_3=" AND first_name like '%$f_name%' ";
}

if($status!="")
{
    $where_4=" AND user_status='$status' ";
}

if($new_users!="")
{
    $where_5=" AND user_activation='$new_users' ";
}

$where_final=$where_1.$where_2.$where_3.$where_4.$where_5;
$login_id = $_SESSION['sda_id'];
//print_r($_SESSION);
?>

<?php
$query_check_approved_users="SELECT count(user_activation) as total_app FROM amz_login WHERE user_activation=1";
$result_check_approved_users = $conn->runsql($query_check_approved_users, $dbcon);
$dataapproved_users=mysqli_fetch_object($result_check_approved_users);

$query_check_rejected_users="SELECT count(user_deletion) as total_rej FROM amz_login WHERE user_deletion=1";
$result_check_rejected_users = $conn->runsql($query_check_rejected_users, $dbcon);
$datarejected_users=mysqli_fetch_object($result_check_rejected_users);

$query_check_pending_users="SELECT count(user_activation) as total_pend FROM amz_login WHERE user_activation=0";
$result_check_pending_users = $conn->runsql($query_check_pending_users, $dbcon);
$datapending_users=mysqli_fetch_object($result_check_pending_users);
?>
<div class="panel">
    <div class="row">
    <div class="small-4 columns">Approved users:<?php echo $dataapproved_users->total_app?></div>
    <div class="small-4 columns">Rejected users:<?php echo $datarejected_users->total_rej?></div>
    <div class="small-4 columns">Pending users:<?php echo $datapending_users->total_pend?></div>
</div></div>
    <?php
//}
?>
<table width="100%">
    <thead>
    <tr id="data_header" align="center">
        <td >S.No.</td>
        <td>User ID</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Team</td>
        <td>User Type</td>
        <td>Status</td>
        <td>Activation</td>
        <td>Action</td>
    </tr>
    </thead>
    <?php
    $query = "SELECT * FROM amz_login WHERE user_deletion=0 ".$where_final."  AND user_type=1 ORDER BY user_name ASC"; // AND ".$where;
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    $available_teams=$_SESSION['team_id'];
    if (mysqli_num_rows($result)) {
        while ($result_row = mysqli_fetch_object($result)) {
            $query_avail_teams = "SELECT team_id FROM amz_user_info WHERE user_id=' $result_row->user_id'";
            $result_avail_teams = $conn->runsql($query_avail_teams, $dbcon);
            $result_row_avail_teams = mysqli_fetch_array($result_avail_teams);
            if(count(array_intersect($available_teams,$result_row_avail_teams))>0)
            {
            $class = $i % 2;
            echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
            ?>
            <td><?php echo $i; //print_r($result_row_avail_teams); ?></td>
            <td><?php echo $result_row->user_name?></td>
            <td><?php echo $result_row->first_name?></td>
            <td><?php echo $result_row->last_name?></td>
            <td><?php echo $result_row->user_type!=3?get_teams($result_row->user_id,$team_array):"--"; ?></td>
            <td><?php echo $user_type[$result_row->user_type]?></td>
            <td><?php //echo $result_row->user_status==1?"Activated":"Deactivated"?>
            <?php if($result_row->user_status==1) {?>
                Activated&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->user_id?>',0);">Disable?</a>
                <?php } else {?>
                Disabled&nbsp;&nbsp;&nbsp;<a onclick="change_status('<?php echo $result_row->user_id?>',1);">Activate?</a>
                <?php }?>
            </td>
            <td>
                <?php if($result_row->user_activation==1) {?>
                Approved&nbsp;&nbsp;&nbsp;<a onclick="do_action('<?php echo $result_row->user_id?>','R');">Reject?</a>
                <?php } else {?>
                <a onclick="do_action('<?php echo $result_row->user_id?>','A');">Approve?</a>&nbsp;&nbsp;&nbsp;<a onclick="do_action('<?php echo $result_row->user_id?>','R');">Reject?</a>
                <?php }?>
            </td>
            <td><a href="#">Edit</a>&nbsp;&nbsp;&nbsp;<a href="#">Delete</a></td>
        </tr>
        <?php
        $i++;
     }
    }
 }
 else {
     ?>
        <tr><td colspan="9" align="center">No results found!</td></tr>
<?php
}

function get_teams($user_id,$team_array)
{
    require_once '../classes/db.class.php';
    $conn = new db();
    $dbcon = $conn->dbConnect();
    $teams="";
    $query="SELECT team_id FROM amz_user_info WHERE user_id='$user_id' AND status='1'";
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    if (mysqli_num_rows($result)) 
    {
        while ($result_row = mysqli_fetch_object($result)) 
        {
            $teams.=$team_array[$result_row->team_id].", ";
        }
        return rtrim($teams,", ");
    }
    else
    {
        return "No teams available";
    }
}
    ?>
        </tr>
</table>



        <link href="../css/theme.default.css" rel="stylesheet">
	<script src="../js/jquery.tablesorter.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
		});
	});

</script>