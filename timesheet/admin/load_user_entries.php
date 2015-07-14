<?php
session_start();
include_once './data_page.php';
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
?>
<script>
function del_ua(id)
    {
        if(confirm('Are you sure to delete?'))
        {
            
            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: "del_user_entries.php?action=del_ua",
                data: "id="+id,
                success: function(msg)
                {
                        var html = $.trim(msg);
                        $('.msgs').html(msg).hide();
                        $('.msgs').fadeIn("slow");
                        $('.msgs').delay(5000).fadeOut("slow");
                        filter_1();
                }
            });
            return false;
        }
     }
</script>
<?php
$where_1 = $where_2 = $where_3 = $where_4 = $where_5=$where_6=$where_final="";

$date_from=$_POST['date_from'];
$date_to=$_POST['date_to'];
$team=$_POST['team'];
$task=$_POST['task'];
$s_task=$_POST['s_task'];
$task_desc=$_POST['task_desc'];
$user=$_POST['user'];

if($date_from=="" && $date_to=="")
{
    
}
elseif ($date_from=="" && $date_to!="") {

}
elseif ($date_from!="" && $date_to=="") {

}
else
{
    $where_1 = " date between '$date_from' AND '$date_to' ";
    $msg = "Between $date_from and $date_to";
}

if(!empty($team))
{
    $team_text=implode(",", $team);
    $where_2=" AND team_id IN ($team_text) ";
}

if(!empty($task))
{
    $task_text=implode(",",$task);
    $where_3=" AND tasks_id IN ($task_text) ";
}

if(!empty($s_task))
{
    $s_task_text=implode(",",$s_task);
    $where_4=" AND sub_task_id IN ($s_task_text) ";
}

if(!empty($task_desc))
{
    $task_desc_text=implode(",",$task_desc);
    $where_5=" AND task_desc IN ($task_desc_text) ";
}

if(!empty($user))
{
    $user_text=implode(",",$user);
    $where_6=" AND user_id IN ($user_text) ";
}
$where_final=$where_1.$where_2.$where_3.$where_4.$where_5.$where_6;
//$user_id = $_SESSION['user_id'];
$date_from = $_POST['date_from'];
$date_to=$_POST['date_to'];

$query = "SELECT * FROM user_tasks WHERE ".$where_final." ORDER BY date DESC"; // AND ".$where;
$file_name=$title="Report_between_".$date_from."_and_".$date_to;
?>
<div class="row" style="width: 99%">
    <div class="small-6 columns"><h5>Report Between <?php echo $date_from;?> And <?php echo $date_to;?></h5></div>
      <div class="small-6 columns" align="right">
        <form action = "export_excel_user_entries.php" method = "post" target="_blank">
	<input type="hidden" name="query" id="query" value="<?php echo $query?>">
	<input type="hidden" name="file_name" id="file_name" value="<?php echo $file_name?>">
        <input type="hidden" name="title" id="title" value="<?php echo $title?>">
        <input type="submit" name="Export_2_excel" id="Export_2_excel" onclick="" value="Export to Excel" class="button tiny">
</form>
</div>
    </div>
</div>
 
<table width="100%" class="tablesorter" >
    <thead>
    <tr id="data_header" align="center">
        <td >S.No.</td>
        <td style="width: 120px;">Date</td>
        <td>User Name</td>
        <td>Team</td>
        <td>Task</td>
        <td>Sub task</td>
        <td>Time</td>
        <td>Count</td>
        <td>Target</td>
        <td>Work units</td>
        <td>WU status</td>
        <td>Devices</td>
        <td>Action</td>
    </tr>
    </thead>
    <?php
     $today_date = date('Y-m-d', $_SERVER['REQUEST_TIME']);
     
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    $tot_time="";//=strtotime("h:i:s",  time());
    $tot_count=0;
    $secs=0;
    $tot_work_units=0.0;
    
    if (mysqli_num_rows($result)) {
        while ($result_row = mysqli_fetch_object($result)) {
            $class = $i % 2;
            echo $i % 2 == 0 ? "<tr id='data_row_even'>" : "<tr id='data_row_odd'>";
            ?>
            <td><?php echo $i ?></td>
            <td><?php echo $result_row->date ?></td>
            <td><?php echo $user_array[$result_row->user_id] ?></td>
            <td><?php echo $team_array[$result_row->team_id] ?></td>
            <td><?php echo $task_array[$result_row->tasks_id] ?></td>
            <td><?php echo $result_row->sub_task_id!=""?$sub_task_array[$result_row->sub_task_id]:"NA" ?></td>
            <td><?php echo $result_row->time?></td>
            <?php
                $secs+= strtotime($result_row->time)-strtotime("00:00:00");
            ?>
            <td><?php echo $count_wu=$result_row->count?></td>
            <?php $tot_count=$tot_count+$result_row->count;?>
            <td><?php echo $result_row->cf!=""?$result_row->cf:"NA"?></td>
            <td><?php
                    if($result_row->wu!="") {
                        echo $result_row->wu;
                        $tot_work_units+=$result_row->wu;
                    } else {
                        echo "NA";
                    }?>
            </td>
            <td><?php echo $result_row->wu_status==0?"Pending":"Up to date"?></td>
            <td><?php echo $result_row->noofdevice!= "" ? $result_row->noofdevice : "-" ?></td>
            <td><a href="#" data-reveal-id="myModal" data-reveal-ajax="./view_user_entries.php?val=<?php echo $result_row->task_id?>">View</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="del_ua('<?php echo $result_row->task_id?>');">Delete</a></td>
        </tr>
        <?php
        $i++;
    }
    ?>
        <tr style="background-color: #f2f2f2;">
        <td colspan="6" align="right">Total</td>
        <td><?php echo $tot_time=sectohr($secs);?></td>
        <td><?php echo $tot_count?></td>
        <td>&nbsp;</td>
        <td><?php echo $tot_work_units?></td>
        <td colspan="3"></td>
        </tr>
        <?php
} else {
    echo "<tr><td align='center' colspan='16'>No Entries available!</td></tr>";
}
?>
</table>
</div></div>

<div id="myModal" class="reveal-modal small" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>

<script>
$(document).foundation();
</script>

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