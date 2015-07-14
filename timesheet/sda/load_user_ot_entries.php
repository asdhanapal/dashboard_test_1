<?php
session_start();
if(isset($_SESSION) && empty($_SESSION)) {
    die("<center>Session Expired!. Press F5 to refresh the page.</center>");
}

include_once './data_page.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$ot_status_array=array(""=>"Pending","1"=>"Approved","2"=>"Rejected");
?>
<script>
function del_ua(id,url)
    {
        if(confirm('Are you sure to delete?'))
        {
            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: url+"?action=del_ua",
                data: "id="+id,
                success: function(msg)
                {
                        var html = $.trim(msg);
                        $('.msgs').html(html);
                        var date_for_rec=$('#date').val();
                        check_remaining_hrs(date_for_rec);
                        filter();
                }
            });
            return false;
        }
     }
</script>
<?php
$user_id = $_SESSION['sda_id'];
$working_date = $_POST['date']; //date("Y-d-m", strtotime($_POST['date']));
?>
<div class="row collapse">
    <div class="small-9 columns">
<?php
if($_POST['date']!="")
echo "<h5>Report on ".$_POST['date']." for ".$_SESSION['sda_name']."</h5>";
else
    echo "<h5>Select date to view the report</h5>";


$today_date = date('Y-m-d', $_SERVER['REQUEST_TIME']);
$query = "SELECT * FROM user_tasks_ot WHERE user_id='$user_id'  AND date='$working_date' ORDER BY date DESC"; // AND ".$where;
$file_name=$title="Report on ".$working_date;
//$file_name="";
?>
</div>
    <!--<div class="small-1 columns">
        <form action = "export_excel_user_single_entry.php" method = "post" target="_blank">
	<input type="hidden" name="query" id="query" value="<?php echo $query?>">
	<input type="hidden" name="file_name" id="file_name" value="<?php echo $file_name?>">
        <input type="hidden" name="title" id="title" value="<?php echo $title?>">
        <input type="submit" name="Export_2_excel" id="Export_2_excel" onclick="" value="Export to Excel" class="button tiny">
</form>
</div>-->
 </div>
<table width="100%">
    <tr style="background-color: #d3d3d3;">
        <td colspan="10">
            <b>OT Entries:</b>
        </td>
    </tr>
    <tr id="data_header" align="center">
        <td >S.No.</td>
        <td style="width: 120px;">Date</td>
        <td>Team</td>
        <td>Task</td>
        <td>Sub task</td>
        <td>Task desc</td>
        <td>Time</td>
        <td>OT status</td>
        <td>admin cmds</td>
        <td>Action</td>
    </tr>
    <?php
    
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    if (mysqli_num_rows($result)) {
        while ($result_row = mysqli_fetch_object($result)) {
            $class = $i % 2;
            echo $i % 2 == 0 ? "<tr id='data_row_even'>" : "<tr id='data_row_odd'>";
            ?>
            <td><?php echo $i ?></td>
            <td><?php echo $result_row->date ?></td>
            <td><?php echo $team_array[$result_row->team_id] ?></td>
            <td><?php echo $task_array[$result_row->tasks_id] ?></td>
            <td><?php echo $result_row->sub_task_id!=""? $sub_task_array[$result_row->sub_task_id]:"N/A"; ?></td>
            <td><?php echo $result_row->task_desc!=""? $task_desc_array[$result_row->task_desc]:"N/A"; ?></td>
            <td><?php echo $result_row->time?></td>
            <td><?php echo $status_array[$result_row->ot_status]?></td>
            <td><?php echo $result_row->admin_cmds!=""?$result_row->admin_cmds:"N/A"?></td>
            <td align="center"><a href="#" data-reveal-id="myModalot<?php echo $i ?>">View</a>&nbsp;&nbsp;<a href="#" onclick="del_ua('<?php echo $result_row->task_id?>','del_user_ot_entries.php');">Delete</a></td>
        </tr>
        
        <div id="myModalot<?php echo $i ?>" class="reveal-modal small" data-reveal>
            <div class="Table">
                        <!--<div class="Title"></div>-->
                        <div class="Heading"><div class="Cell">Field</div><div class="Cell">Value</div></div>
                        <div class="Row_odd"><div class="Cell">Date</div><div class="Cell"><?php echo $result_row->date ?></div></div>
                        <div class="Row_even"><div class="Cell">Team</div><div class="Cell"><?php echo $team_array[$result_row->team_id] ?></div></div>
                        <div class="Row_odd"><div class="Cell">Task</div><div class="Cell"><?php echo $task_array[$result_row->tasks_id] ?></div></div>
                        <div class="Row_even"><div class="Cell">Sub task</div><div class="Cell"><?php echo $result_row->sub_task_id!=""? $sub_task_array[$result_row->sub_task_id]:"N/A";?></div></div>
                        <div class="Row_odd"><div class="Cell">Task desc.</div><div class="Cell"><?php echo $result_row->task_desc!=""? $task_desc_array[$result_row->task_desc]:"N/A";?></div></div>
                        <div class="Row_even"><div class="Cell">Count</div><div class="Cell"><?php echo $result_row->count==0?"N/A":$result_row->count?></div></div>
                        <div class="Row_odd"><div class="Cell">Time</div><div class="Cell"><?php echo $result_row->time ?></div></div>
                        <div class="Row_even"><div class="Cell">Comments</div><div class="Cell"><?php echo $result_row->cmds!=""?$result_row->cmds:"NA" ?></div></div>
                        <div class="Row_odd"><div class="Cell">OT Status</div><div class="Cell"><?php echo $ot_status_array[$result_row->ot_status] ?></div></div>
                        <div class="Row_even"><div class="Cell">Action taken by</div><div class="Cell"><?php echo $result_row->act_by!=""?$user_array[$result_row->act_by]:"N/A"?></div></div>
                        <div class="Row_odd"><div class="Cell">Comments from act person</div><div class="Cell"><?php echo $result_row->admin_cmds!=""?$result_row->admin_cmds:"N/A" ?></div></div>
                        <div class="Row_even"><div class="Cell">Entered on</div><div class="Cell"><?php echo $result_row->create_date ?></div></div>
                        <div class="Row_odd"><div class="Cell">Last modified on</div><div class="Cell"><?php echo $result_row->maintain_date ?></div></div>
                        <div class="Row_even"><div class="Cell">Modified by</div><div class="Cell"><?php echo $result_row->modified_by==""?"You":$user_array[$result_row->modified_by] ?></div></div>
                    </div>
            <a class="close-reveal-modal">&#215;</a>
        </div>
        <?php
        $i++;
    }
} else {
    echo "<tr><td align='center' colspan='16'>No Entries available!</td></tr>";
}

$query = "SELECT * FROM user_tasks WHERE user_id='$user_id'  AND date='$working_date' ORDER BY date DESC"; // AND ".$where;
$file_name=$title="Report on ".$working_date;
?>
</table>
<hr>        
<table width="100%">
        
        <tr style="background-color: #d3d3d3;">
        <td colspan="10">
            <b>Working Hours Entries:</b>
        </td>
    </tr>

    <tr id="data_header" align="center">
        <td >S.No.</td>
        <td style="width: 120px;">Date</td>
        <td>Team</td>
        <td>Task</td>
        <td>Sub task</td>
        <td>Task desc</td>
        <td>Time</td>
        <td>Comments</td>
        <td>On Time?</td>
<!--        <td>Last modify by</td>-->
        <td>Action</td>
    </tr>
    <?php
    
    $result = $conn->runsql($query, $dbcon);
    $i = 1;
    if (mysqli_num_rows($result)) {
        while ($result_row = mysqli_fetch_object($result)) {
            $class = $i % 2;
            echo $i % 2 == 0 ? "<tr id='data_row_even'>" : "<tr id='data_row_odd'>";
            ?>
            <td><?php echo $i ?></td>
            <td><?php echo $result_row->date ?></td>
            <td><?php echo $team_array[$result_row->team_id] ?></td>
            <td><?php echo $task_array[$result_row->tasks_id] ?></td>
            <td><?php echo $result_row->sub_task_id!=""? $sub_task_array[$result_row->sub_task_id]:"N/A"; ?></td>
            <td><?php echo $result_row->task_desc!=""? $task_desc_array[$result_row->task_desc]:"N/A"; ?></td>
            <td><?php echo $result_row->time?></td>
            <td><?php //echo $result_row->cmds?></td>
            <td><?php echo $result_row->on_time == "Y" ? "Yes" : "No" ?></td>
<!--            <td><?php //echo $result_row->modified_by!=""? $result_row->modified_by:"N/A"?></td>                <a href="#">Edit</a>&nbsp;&nbsp;-->
<!--            <td align="center"><?php //echo $result_row->maintain_date?></td>-->
            <td align="center"><a href="#" data-reveal-id="myModal<?php echo $i ?>">View</a>&nbsp;&nbsp;<a href="#" onclick="del_ua('<?php echo $result_row->task_id?>','del_user_entries.php');">Delete</a></td>
        </tr>
        <div id="myModal<?php echo $i ?>" class="reveal-modal small" data-reveal>
            <div class="Table">
                        <!--<div class="Title"></div>-->
                        <div class="Heading"><div class="Cell">Field</div><div class="Cell">Value</div></div>
                        <div class="Row_odd"><div class="Cell">Date</div><div class="Cell"><?php echo $result_row->date ?></div></div>
                        <div class="Row_even"><div class="Cell">Team</div><div class="Cell"><?php echo $team_array[$result_row->team_id] ?></div></div>
                        <div class="Row_odd"><div class="Cell">Task</div><div class="Cell"><?php echo $task_array[$result_row->tasks_id] ?></div></div>
                        <div class="Row_even"><div class="Cell">Sub task</div><div class="Cell"><?php echo $result_row->sub_task_id!=""? $sub_task_array[$result_row->sub_task_id]:"N/A";?></div></div>
                        <div class="Row_odd"><div class="Cell">Task desc.</div><div class="Cell"><?php echo $result_row->task_desc!=""? $task_desc_array[$result_row->task_desc]:"N/A";?></div></div>
                        <div class="Row_even"><div class="Cell">Count</div><div class="Cell"><?php echo $result_row->count==0?"N/A":$result_row->count?></div></div>
                        <div class="Row_odd"><div class="Cell">Time</div><div class="Cell"><?php echo $result_row->time ?></div></div>
                        <div class="Row_even"><div class="Cell">On time?</div><div class="Cell"><?php echo $result_row->on_time == "Y" ? "Yes" : "No" ?></div></div>
                        <div class="Row_odd"><div class="Cell">Comments</div><div class="Cell"><?php echo $result_row->cmds ?></div></div>
                        <div class="Row_even"><div class="Cell">Entered on</div><div class="Cell"><?php echo $result_row->create_date ?></div></div>
                        <div class="Row_odd"><div class="Cell">Modified by</div><div class="Cell"><?php echo $result_row->modified_by==""?"You":$user_array[$result_row->modified_by] ?></div></div>
                    </div>
            <a class="close-reveal-modal">&#215;</a>
        </div>
        <?php
        $i++;
    }
} else {
    echo "<tr><td align='center' colspan='16'>No Entries available!</td></tr>";
}
?>
</table>

</div></div>

<script>
$(document).foundation();
</script>


<style type="text/css">
    .Table {
        display: table;
        width: 100%;
        text-align: center;
        background: white;
        margin-bottom: 1.25rem;
        color: #333333;
        font-family: Verdana,Arial,Helvetica,sans-serif;
        font-size: 13px;
        border: solid 1px #dddddd;
    }
    .Title {
        display: table-caption;
        text-align: center;
        font-weight: bold; 
        font-size: larger;
    } 
    .Heading { 
        background: none repeat scroll 0 0 #CCFFCC;
        color: #000000;
        font-size: 14px;
        height: 20px;
        display: table-row;
        text-align: center;
    }
    .Row_odd { 
        background: none repeat scroll 0 0 #E9FFFF;
        display: table-row;
        height: 10px;
    }
    .Row_even { 
        background: none repeat scroll 0 0 #FFFFFF;
        height: 10px;
        display: table-row;
    }
    .Cell { 
        display: table-cell;
        border: 2px;
        border-width: thin;
        padding-left: 5px;
        padding-right: 5px;
        padding: 10px;
        margin-left: 20px;
    }
    
    .row_odd:hover, .row_even:hover {
        display: table-row;
        visibility: visible;
    background: none repeat scroll 0 0 #CCFFCC;
    color: #3366CC;
    height: 10px;
}
</style>
