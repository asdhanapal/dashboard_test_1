<?php
include_once './data_page.php';
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
$id=$_GET['val'];
$query = "SELECT * FROM user_tasks WHERE task_id='$id'"; // AND ".$where;
$result = $conn->runsql($query, $dbcon);
$result_row = mysqli_fetch_object($result);
?>
<div class="Table">
    <div class="Heading"><div class="Cell">Field</div><div class="Cell">Value</div></div>
    <div class="Row_odd"><div class="Cell">Date</div><div class="Cell"><?php echo $result_row->date ?></div></div>
    <div class="Row_even"><div class="Cell">User</div><div class="Cell"><?php echo $user_array[$result_row->user_id] ?></div></div>
    <div class="Row_odd"><div class="Cell">Team</div><div class="Cell"><?php echo $team_array[$result_row->team_id] ?></div></div>
    <div class="Row_even"><div class="Cell">Task</div><div class="Cell"><?php echo $task_array[$result_row->tasks_id] ?></div></div>
    <div class="Row_odd"><div class="Cell">Sub task</div><div class="Cell"><?php echo $result_row->sub_task_id!=""?$sub_task_array[$result_row->sub_task_id]:"NA" ?></div></div>
    <div class="Row_even"><div class="Cell">Task desc.</div><div class="Cell"><?php echo $result_row->task_desc!=""? $task_desc_array[$result_row->task_desc]:"NA";?></div></div>
    <div class="Row_odd"><div class="Cell">Count</div><div class="Cell"><?php echo $result_row->count==0?"NA":$result_row->count?></div></div>
    <div class="Row_even"><div class="Cell">Con. fac.</div><div class="Cell"><?php echo $result_row->cf==""?"NA":$result_row->cf?></div></div>
    <div class="Row_odd"><div class="Cell">Work units</div><div class="Cell"><?php echo $result_row->wu==""?"NA":$result_row->wu?></div></div>
    <div class="Row_even"><div class="Cell">Time</div><div class="Cell"><?php echo $result_row->time ?></div></div>
    <div class="Row_odd"><div class="Cell">On time?</div><div class="Cell"><?php echo $result_row->on_time == "Y" ? "Yes" : "No" ?></div></div>
    <div class="Row_even"><div class="Cell">Comments</div><div class="Cell"><?php echo $result_row->cmds ?></div></div>
    <div class="Row_odd"><div class="Cell">Entered on</div><div class="Cell"><?php echo $result_row->create_date ?></div></div>
    <div class="Row_even"><div class="Cell">Last modified on</div><div class="Cell"><?php echo $result_row->maintain_date==$result_row->create_date?"NA":$result_row->maintain_date; ?></div></div>
    <div class="Row_odd"><div class="Cell">Modified by</div><div class="Cell"><?php echo $result_row->modified_by==""?"NA":$user_array[$result_row->modified_by] ?></div></div>
</div>



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
